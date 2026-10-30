<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\MasterSemester;
use App\Models\MasterTahunAkademik;
use App\Models\Pembayaran;
use App\Models\TransaksiKeuangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['user', 'tagihan', 'tahunAkademik'])->has('tagihan')->latest()->get();

        // Ambil Data Master Tahun Akademik & Semester untuk modal alokasi pembayaran
        $tahunAkademiks = MasterTahunAkademik::with('semesters')->orderBy('tahun', 'desc')->get();
        $activeTa = MasterTahunAkademik::with('semesters')->where('is_active', true)->first() ?: $tahunAkademiks->first();
        $activeSemester = MasterSemester::with('tahunAkademik')->where('is_active', true)->first();

        return view('backend.keuangan.verifikasi.index', compact('pembayarans', 'tahunAkademiks', 'activeTa', 'activeSemester'));
    }

    public function approve(Request $request, $id)
    {
        $pembayaran = Pembayaran::with(['user', 'tagihan'])->findOrFail($id);

        $request->validate([
            'nominal' => 'nullable|numeric|min:0',
            'bulan_alokasi' => 'nullable',
            'catatan_alokasi' => 'nullable|string',
            'tahun_akademik_id' => 'nullable',
        ]);

        $verifiedNominal = $request->filled('nominal') ? (float)$request->input('nominal') : (float)$pembayaran->nominal;
        if ($verifiedNominal <= 0) {
            $verifiedNominal = (float)$pembayaran->nominal;
        }

        $bulanAlokasi = $request->input('bulan_alokasi');
        if (is_string($bulanAlokasi)) {
            $bulanAlokasi = json_decode($bulanAlokasi, true);
        }
        $bulanAlokasi = is_array($bulanAlokasi) ? array_values(array_filter($bulanAlokasi)) : null;

        $tahunAkademikId = $request->input('tahun_akademik_id');
        $catatanAlokasi = $request->input('catatan_alokasi');

        // Jika form alokasi tidak dipilih secara manual, gunakan bulan_alokasi yang sudah ada pada pembayaran
        if (empty($bulanAlokasi) && !empty($pembayaran->bulan_alokasi)) {
            $bulanAlokasi = is_array($pembayaran->bulan_alokasi) ? $pembayaran->bulan_alokasi : json_decode($pembayaran->bulan_alokasi, true);
        }

        // Jika masih kosong, coba deteksi dari nama/tanggal tagihan
        if (empty($bulanAlokasi) && $pembayaran->tagihan) {
            $namaLower = strtolower($pembayaran->tagihan->nama_tagihan ?? '');
            $monthNames = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
                'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
                'jun' => 6, 'jul' => 7, 'ags' => 8, 'sep' => 9,
                'okt' => 10, 'nov' => 11, 'des' => 12,
            ];
            $detectedMonth = null;
            foreach ($monthNames as $mKey => $mVal) {
                if (str_contains($namaLower, $mKey)) {
                    $detectedMonth = $mVal;
                    break;
                }
            }
            $createdYear = $pembayaran->created_at ? $pembayaran->created_at->year : date('Y');
            if ($detectedMonth) {
                $bulanAlokasi = [sprintf('%04d-%02d', $createdYear, $detectedMonth)];
            }
        }
        
        if ($pembayaran->status !== 'verified') {
            // Generate Kwitansi Number (INV/YYYYMM/UserID+Random)
            $kwitansiNo = 'INV/' . date('Ym') . '/' . str_pad($pembayaran->user_id, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));

            // Kurangi sisa tagihan & perbarui status tagihan menjadi lunas atau sebagian (partial)
            $tagihan = $pembayaran->tagihan;
            $isPartial = false;
            if ($tagihan) {
                $totalTerbayarLain = (float) Pembayaran::where('tagihan_id', $tagihan->id)
                    ->where('status', 'verified')
                    ->where('id', '!=', $pembayaran->id)
                    ->sum('nominal');

                $totalTerbayar = $totalTerbayarLain + $verifiedNominal;
                $sisaTagihan = max(0, (float)$tagihan->nominal_total - $totalTerbayar);

                $tagihan->sisa_tagihan = $sisaTagihan;
                if ($sisaTagihan <= 0) {
                    $tagihan->status = 'lunas';
                    $tagihan->jenis_tagihan = 'lunas';
                } else {
                    $tagihan->status = 'sebagian';
                    $tagihan->jenis_tagihan = 'cicilan';
                    $isPartial = true;
                }
                $tagihan->save();
            }

            $pembayaran->update([
                'status' => 'verified',
                'nominal' => $verifiedNominal,
                'jenis_pembayaran' => $isPartial ? 'cicilan' : 'lunas',
                'kwitansi' => $kwitansiNo,
                'bulan_alokasi' => $bulanAlokasi,
                'tahun_akademik_id' => $tahunAkademikId,
                'catatan_alokasi' => $catatanAlokasi,
            ]);

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
                    Log::warning('Gagal upload kwitansi ke Drive Keuangan, mencoba Drive Utama: ' . $eKeuangan->getMessage());
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
                Log::error('Gagal upload kwitansi ke Google Drive: ' . $e->getMessage());
            }

            // Send Email to Mahasiswa
            try {
                if ($pembayaran->user && $pembayaran->user->email) {
                    Mail::to($pembayaran->user->email)->send(new \App\Mail\PaymentApprovedMail($pembayaran, $pembayaran->user));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send payment approved email: ' . $e->getMessage());
            }

            // Catat ke Buku Kas / Riwayat Transaksi secara otomatis (Audit Trail)
            // UANG FISIK DICATAT SESUAI TANGGAL AKTUAL KAS (Cash Date utuh)
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

                // Tanggal Mutasi Kas: tanggal aktual pembayaran/transfer dibuat (tidak boleh diubah bulannya)
                $tanggalKas = $pembayaran->created_at ?: now();

                // Kode Transaksi Unik: Format TRX-SPP-[ID]-[TIMESTAMP] atau TRX-IN-[ID]-[TIMESTAMP]
                $prefix = $isSpp ? 'TRX-SPP-' : 'TRX-IN-';
                $defaultKode = $prefix . $pembayaran->id . '-' . $tanggalKas->format('YmdHis');

                // Jika sudah pernah ada kode transaksi sebelumnya, pertahankan
                $existingTx = TransaksiKeuangan::where('pembayaran_id', $pembayaran->id)->first();
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

                // Deskripsi Rincian dengan Info Bulan Alokasi jika ada
                $tagihanNama = $pembayaran->tagihan ? $pembayaran->tagihan->nama_tagihan : 'Tagihan Mahasiswa';
                $alokasiLabelStr = '';
                if (!empty($bulanAlokasi)) {
                    $labels = $this->formatBulanAlokasiLabels($bulanAlokasi);
                    $alokasiLabelStr = " (Alokasi: " . implode(', ', $labels) . ")";
                }

                $statusKet = $isPartial ? " (Sebagian / Cicilan)" : " (Lunas)";
                $deskripsi = "Pembayaran " . ($isSpp ? "SPP" : $tagihanNama) . " Terverifikasi{$statusKet}{$alokasiLabelStr} - {$tagihanNama}" .
                    ($pembayaran->catatan ? " ({$pembayaran->catatan})" : "");

                // Admin Pencatat
                $adminUser = auth()->user();

                TransaksiKeuangan::updateOrCreate(
                    ['pembayaran_id' => $pembayaran->id],
                    [
                        'kode_transaksi' => $kodeTransaksi,
                        'jenis_transaksi' => 'masuk',
                        'kategori' => $kategori,
                        'nominal' => $pembayaran->nominal,
                        'tanggal_transaksi' => $tanggalKas,
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
                Log::error('Gagal mencatat transaksi keuangan otomatis: ' . $eTx->getMessage());
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan alokasi bulan SPP berhasil dicatat.');
    }

    /**
     * Perbarui Bulan Alokasi SPP pada pembayaran yang sudah ada
     */
    public function updateAlokasi(Request $request, $id)
    {
        $pembayaran = Pembayaran::with(['tagihan', 'transaksiKeuangan'])->findOrFail($id);

        $request->validate([
            'bulan_alokasi' => 'nullable|array',
            'tahun_akademik_id' => 'nullable|exists:master_tahun_akademik,id',
            'catatan_alokasi' => 'nullable|string|max:500',
        ]);

        $bulanAlokasi = (array) $request->input('bulan_alokasi', []);
        $bulanAlokasi = array_values(array_filter($bulanAlokasi));

        $pembayaran->update([
            'bulan_alokasi' => !empty($bulanAlokasi) ? $bulanAlokasi : null,
            'tahun_akademik_id' => $request->input('tahun_akademik_id'),
            'catatan_alokasi' => $request->input('catatan_alokasi'),
        ]);

        // Perbarui deskripsi riwayat transaksi kas jika ada
        if ($pembayaran->transaksiKeuangan) {
            $isSpp = ($pembayaran->tagihan && (
                strtoupper(trim($pembayaran->tagihan->nama_tagihan)) === 'SPP' ||
                stripos($pembayaran->tagihan->nama_tagihan, 'spp') !== false
            ));
            $tagihanNama = $pembayaran->tagihan ? $pembayaran->tagihan->nama_tagihan : 'Tagihan Mahasiswa';
            $alokasiLabelStr = '';
            if (!empty($bulanAlokasi)) {
                $labels = $this->formatBulanAlokasiLabels($bulanAlokasi);
                $alokasiLabelStr = " (Alokasi: " . implode(', ', $labels) . ")";
            }

            $deskripsi = "Pembayaran " . ($isSpp ? "SPP" : $tagihanNama) . " Terverifikasi{$alokasiLabelStr} - {$tagihanNama}" .
                ($pembayaran->catatan ? " ({$pembayaran->catatan})" : "");

            $pembayaran->transaksiKeuangan->update([
                'deskripsi' => $deskripsi,
            ]);
        }

        return back()->with('success', 'Bulan alokasi pembayaran SPP berhasil diperbarui.');
    }

    public function reject($id)
    {
        $pembayaran = Pembayaran::with(['tagihan'])->findOrFail($id);
        
        $wasVerified = ($pembayaran->status === 'verified');

        $pembayaran->update([
            'status' => 'rejected',
            'bulan_alokasi' => null,
        ]);

        // Jika pembayaran ini sebelumnya berstatus verified, kembalikan nominal ke sisa tagihan
        if ($wasVerified && $pembayaran->tagihan) {
            $tagihan = $pembayaran->tagihan;
            $totalTerbayarLain = (float) Pembayaran::where('tagihan_id', $tagihan->id)
                ->where('status', 'verified')
                ->where('id', '!=', $pembayaran->id)
                ->sum('nominal');
            $sisa = max(0, (float)$tagihan->nominal_total - $totalTerbayarLain);
            $tagihan->sisa_tagihan = $sisa;
            if ($sisa <= 0 && $totalTerbayarLain > 0) {
                $tagihan->status = 'lunas';
                $tagihan->jenis_tagihan = 'lunas';
            } elseif ($totalTerbayarLain > 0) {
                $tagihan->status = 'sebagian';
                $tagihan->jenis_tagihan = 'cicilan';
            } else {
                $tagihan->status = 'belum lunas';
            }
            $tagihan->save();
        }

        // Hapus record transaksi di Buku Kas agar saldo dan riwayat tetap akurat
        TransaksiKeuangan::where('pembayaran_id', $pembayaran->id)->delete();
        
        return back()->with('success', 'Pembayaran telah ditolak dan transaksi Buku Kas telah disesuaikan.');
    }

    public function kwitansiDrive($id)
    {
        $pembayaran = Pembayaran::with(['user', 'tagihan'])->findOrFail($id);
        
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
        $pembayaran = Pembayaran::findOrFail($id);
        
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

    /**
     * Format array string tahun-bulan menjadi label yang mudah dibaca
     */
    private function formatBulanAlokasiLabels(array $bulanList): array
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $labels = [];
        foreach ($bulanList as $item) {
            if (preg_match('/^(\d{4})-(\d{2})$/', $item, $matches)) {
                $mNum = (int)$matches[2];
                $yNum = $matches[1];
                $mName = $monthNames[$mNum] ?? "Bulan {$mNum}";
                $labels[] = "{$mName} {$yNum}";
            } elseif (is_numeric($item)) {
                $mNum = (int)$item;
                $labels[] = $monthNames[$mNum] ?? "Bulan {$mNum}";
            } else {
                $labels[] = (string)$item;
            }
        }
        return $labels;
    }
}
