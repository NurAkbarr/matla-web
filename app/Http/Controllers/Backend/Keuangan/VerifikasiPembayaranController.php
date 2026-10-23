<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = \App\Models\Pembayaran::with(['user', 'tagihan'])->has('tagihan')->latest()->get();

        return view('backend.keuangan.verifikasi.index', compact('pembayarans'));
    }

    public function approve($id)
    {
        $pembayaran = \App\Models\Pembayaran::with(['user', 'tagihan'])->findOrFail($id);
        
        if ($pembayaran->status !== 'verified') {
            // Generate Kwitansi Number (INV/YYYYMM/UserID+Random)
            $kwitansiNo = 'INV/' . date('Ym') . '/' . str_pad($pembayaran->user_id, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));

            $pembayaran->update([
                'status' => 'verified',
                'kwitansi' => $kwitansiNo
            ]);

            // Kurangi sisa tagihan & perbarui status tagihan menjadi lunas bila lunas
            $tagihan = $pembayaran->tagihan;
            if ($tagihan) {
                $tagihan->sisa_tagihan -= $pembayaran->nominal;
                if ($tagihan->sisa_tagihan <= 0 || strtolower($pembayaran->jenis_pembayaran) === 'lunas') {
                    $tagihan->sisa_tagihan = 0;
                    $tagihan->status = 'lunas';
                }
                $tagihan->save();
            }

            // Generate PDF and Upload to Google Drive (Background)
            try {
                $pembayaran->load(['user', 'tagihan']);
                $pdf = Pdf::loadView('mahasiswa.pembayaran.kwitansi', compact('pembayaran'));
                
                $angkatan = $pembayaran->user->angkatan ?? 'Tanpa Angkatan';
                $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $pembayaran->user->name ?? 'Unknown');
                
                // Path di dalam Google Drive: Kwitansi_Pembayaran / 2022 / Mhsasep / Kwitansi-INV-....pdf
                $drivePath = "Kwitansi_Pembayaran/{$angkatan}/{$nama}/Kwitansi_{$kwitansiNo}.pdf";
                
                // 1. Coba upload ke Google Drive Keuangan
                try {
                    Storage::disk('google_keuangan')->put($drivePath, $pdf->output());
                } catch (\Exception $eKeuangan) {
                    \Illuminate\Support\Facades\Log::warning('Gagal upload kwitansi ke Drive Keuangan, mencoba Drive Utama: ' . $eKeuangan->getMessage());
                    // 2. Fallback ke Google Drive Utama (kampus)
                    $mainDrivePath = "Keuangan/{$drivePath}";
                    Storage::disk('google')->put($mainDrivePath, $pdf->output());
                }

                // 3. Simpan juga salinan lokal sebagai cadangan
                try {
                    Storage::disk('public')->put("kwitansi/Kwitansi_{$kwitansiNo}.pdf", $pdf->output());
                } catch (\Exception $eLocal) {
                    // Ignore local storage error
                }
            } catch (\Exception $e) {
                // Ignore PDF upload errors so it doesn't break the approval process
                \Illuminate\Support\Facades\Log::error('Gagal upload kwitansi ke Google Drive: ' . $e->getMessage());
            }

            // Send Email to Mahasiswa
            try {
                if ($pembayaran->user && $pembayaran->user->email) {
                    \Illuminate\Support\Facades\Mail::to($pembayaran->user->email)->send(new \App\Mail\PaymentApprovedMail($pembayaran, $pembayaran->user));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send payment approved email: ' . $e->getMessage());
            }

            // Catat ke Buku Kas / Riwayat Transaksi secara otomatis (Audit Trail)
            try {
                $pembayaran->loadMissing(['user', 'tagihan']);

                // Cek apakah tagihan SPP
                $isSpp = ($pembayaran->tagihan && (
                    strtoupper(trim($pembayaran->tagihan->nama_tagihan)) === 'SPP' ||
                    stripos($pembayaran->tagihan->nama_tagihan, 'spp') !== false ||
                    stripos($pembayaran->jenis_pembayaran, 'spp') !== false
                ));

                // Kategori Transaksi
                $kategori = $isSpp ? 'Pembayaran SPP' : ($pembayaran->tagihan ? 'Tagihan ' . $pembayaran->tagihan->nama_tagihan : 'Pembayaran Mahasiswa');

                // Kode Transaksi Unik: Format TRX-SPP-[ID]-[TIMESTAMP] atau TRX-IN-[ID]-[TIMESTAMP]
                $now = now();
                $prefix = $isSpp ? 'TRX-SPP-' : 'TRX-IN-';
                $defaultKode = $prefix . $pembayaran->id . '-' . $now->format('YmdHis');

                // Jika sudah pernah ada kode transaksi sebelumnya, pertahankan
                $existingTx = \App\Models\TransaksiKeuangan::where('pembayaran_id', $pembayaran->id)->first();
                $kodeTransaksi = $existingTx ? $existingTx->kode_transaksi : $defaultKode;

                // Subjek / Pihak Terkait: Nama Mahasiswa, NIM, dan Program Studi
                $mhs = $pembayaran->user;
                $prodi = $mhs ? ($mhs->education['program_studi'] ?? ($mhs->prodi ?? null)) : null;
                $subjekParts = [];
                if ($mhs) {
                    $subjekParts[] = $mhs->name;
                    if (!empty($mhs->nim)) {
                        $subjekParts[] = "(NIM: {$mhs->nim})";
                    }
                } else {
                    $subjekParts[] = 'Mahasiswa';
                }
                $subjek = implode(' ', $subjekParts);
                if (!empty($prodi)) {
                    $subjek .= " • {$prodi}";
                }

                // Deskripsi Rincian
                $tagihanNama = $pembayaran->tagihan ? $pembayaran->tagihan->nama_tagihan : 'Tagihan Mahasiswa';
                $deskripsi = "Pembayaran " . ($isSpp ? "SPP" : $tagihanNama) . " Terverifikasi - {$tagihanNama}" .
                    ($pembayaran->catatan ? " ({$pembayaran->catatan})" : "");

                // Admin Pencatat
                $adminUser = auth()->user();

                \App\Models\TransaksiKeuangan::updateOrCreate(
                    ['pembayaran_id' => $pembayaran->id],
                    [
                        'kode_transaksi' => $kodeTransaksi,
                        'jenis_transaksi' => 'masuk',
                        'kategori' => $kategori,
                        'nominal' => $pembayaran->nominal,
                        'tanggal_transaksi' => $now,
                        'metode_pembayaran' => 'Transfer Bank',
                        'subjek' => $subjek,
                        'user_id' => $pembayaran->user_id,
                        'tagihan_id' => $pembayaran->tagihan_id,
                        'admin_id' => $adminUser ? $adminUser->id : null,
                        'admin_nama' => $adminUser ? $adminUser->name : 'Admin Keuangan',
                        'deskripsi' => $deskripsi,
                        'bukti' => $pembayaran->bukti_tf,
                        'no_referensi' => $kwitansiNo,
                        'status' => 'verified',
                    ]
                );
            } catch (\Exception $eTx) {
                \Illuminate\Support\Facades\Log::error('Gagal mencatat transaksi keuangan otomatis: ' . $eTx->getMessage());
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan otomatis tercatat ke Buku Kas & Riwayat Transaksi.');
    }

    public function reject($id)
    {
        $pembayaran = \App\Models\Pembayaran::with(['tagihan'])->findOrFail($id);
        
        $wasVerified = ($pembayaran->status === 'verified');

        $pembayaran->update(['status' => 'rejected']);

        // Jika pembayaran ini sebelumnya berstatus verified, kembalikan nominal ke sisa tagihan
        if ($wasVerified && $pembayaran->tagihan) {
            $tagihan = $pembayaran->tagihan;
            $tagihan->sisa_tagihan += $pembayaran->nominal;
            if ($tagihan->sisa_tagihan > 0) {
                $tagihan->status = 'belum lunas';
            }
            $tagihan->save();
        }

        // Hapus record transaksi di Buku Kas agar saldo dan riwayat tetap akurat
        \App\Models\TransaksiKeuangan::where('pembayaran_id', $pembayaran->id)->delete();
        
        return back()->with('success', 'Pembayaran telah ditolak dan transaksi Buku Kas telah disesuaikan.');
    }

    public function kwitansiDrive($id)
    {
        $pembayaran = \App\Models\Pembayaran::with(['user', 'tagihan'])->findOrFail($id);
        
        if (!$pembayaran->kwitansi) {
            return back()->with('error', 'Kwitansi belum terbit.');
        }

        $angkatan = $pembayaran->user->angkatan ?? 'Tanpa Angkatan';
        $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $pembayaran->user->name ?? 'Unknown');
        $kwitansiNo = $pembayaran->kwitansi;
        
        $drivePath = "Kwitansi_Pembayaran/{$angkatan}/{$nama}/Kwitansi_{$kwitansiNo}.pdf";
        $mainDrivePath = "Keuangan/{$drivePath}";
        
        // 1. Coba ambil URL dari Google Drive Keuangan
        try {
            if (Storage::disk('google_keuangan')->exists($drivePath)) {
                $url = Storage::disk('google_keuangan')->url($drivePath);
                if ($url) {
                    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $m)) {
                        $url = "https://drive.google.com/file/d/{$m[1]}/view";
                    }
                    return redirect()->away($url);
                }
            }
        } catch (\Exception $e) {
            // Lanjut ke fallback
        }

        // 2. Coba ambil URL dari Google Drive Utama
        try {
            if (Storage::disk('google')->exists($mainDrivePath)) {
                $url = Storage::disk('google')->url($mainDrivePath);
                if ($url) {
                    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $m)) {
                        $url = "https://drive.google.com/file/d/{$m[1]}/view";
                    }
                    return redirect()->away($url);
                }
            }
        } catch (\Exception $e) {
            // Lanjut ke fallback
        }

        // 3. Fallback: jika di drive belum ada, stream/tampilkan langsung PDF kwitansi
        $pdf = Pdf::loadView('mahasiswa.pembayaran.kwitansi', compact('pembayaran'));
        return $pdf->stream("Kwitansi_{$kwitansiNo}.pdf");
    }

    public function lihatBukti($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        if (!$pembayaran->bukti_tf) {
            return back()->with('error', 'Bukti transfer tidak ditemukan.');
        }

        // Jika URL eksternal (Google Drive / http)
        if (str_starts_with($pembayaran->bukti_tf, 'http')) {
            $url = $pembayaran->bukti_tf;
            // Ubah link direct media Google Drive menjadi link viewer resmi agar tidak tampil hitam/raw
            if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $m)) {
                $url = "https://drive.google.com/file/d/{$m[1]}/view";
            }
            return redirect()->away($url);
        }

        // Jika path lokal di storage public
        $filePath = storage_path('app/public/' . $pembayaran->bukti_tf);
        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath) ?: 'image/jpeg';
            return response()->file($filePath, ['Content-Type' => $mimeType]);
        }

        return back()->with('error', 'File bukti transfer tidak ditemukan di server.');
    }
}
