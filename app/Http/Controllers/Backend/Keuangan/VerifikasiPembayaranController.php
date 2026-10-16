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
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        if ($pembayaran->status !== 'verified') {
            // Generate Kwitansi Number (INV/YYYYMM/UserID+Random)
            $kwitansiNo = 'INV/' . date('Ym') . '/' . str_pad($pembayaran->user_id, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));

            $pembayaran->update([
                'status' => 'verified',
                'kwitansi' => $kwitansiNo
            ]);

            // Kurangi sisa tagihan
            $tagihan = $pembayaran->tagihan;
            if ($tagihan) {
                $tagihan->sisa_tagihan -= $pembayaran->nominal;
                if ($tagihan->sisa_tagihan <= 0) {
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
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        if ($pembayaran->status !== 'rejected') {
            $pembayaran->update(['status' => 'rejected']);
        }
        
        return back()->with('success', 'Pembayaran telah ditolak.');
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

        // Jika URL eksternal (Google Drive / http), redirect langsung
        if (str_starts_with($pembayaran->bukti_tf, 'http')) {
            return redirect()->away($pembayaran->bukti_tf);
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
