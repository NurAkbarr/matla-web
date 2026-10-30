<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MasterSemester;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('mahasiswa.pembayaran.index');
    }

    public function create()
    {
        return view('mahasiswa.pembayaran.create');
    }

    public function tagihan()
    {
        $user = auth()->user();

        // Otomasi Deteksi Tunggakan: Pastikan bulan semester aktif yang belum lunas terbit sebagai tagihan aktif
        $this->syncActiveSemesterTagihans($user);

        $tagihans = Tagihan::with(['pembayarans' => function($q) {
            $q->where('status', '!=', 'rejected')->orderBy('created_at', 'asc');
        }])->where('user_id', $user->id)->latest()->get();

        return view('mahasiswa.pembayaran.tagihan', compact('tagihans'));
    }

    public function konfirmasiForm($id)
    {
        $tagihan = Tagihan::where('user_id', auth()->id())->findOrFail($id);
        return view('mahasiswa.pembayaran.konfirmasi', compact('tagihan'));
    }

    public function submitKonfirmasi(Request $request, $id)
    {
        $tagihan = Tagihan::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'jenis_pembayaran' => 'required|in:lunas,cicilan',
            'nominal' => 'required|numeric|min:1',
            'bukti_tf' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'berkas_pendukung' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'catatan' => 'nullable|string'
        ]);

        if ($tagihan->jenis_tagihan === 'lunas' && $request->jenis_pembayaran === 'cicilan') {
            return back()->withErrors(['jenis_pembayaran' => 'Tagihan ini wajib lunas (sekali bayar) dan tidak dapat dicicil.'])->withInput();
        }

        $nominal = $request->nominal;
        // Strict validation: if Lunas, force the nominal to match remaining bill exactly
        if ($request->jenis_pembayaran === 'lunas') {
            $nominal = $tagihan->sisa_tagihan;
        }

        $user = auth()->user();
        $angkatan = $user->angkatan ?? 'Tanpa Angkatan';
        $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $user->name ?? 'Unknown');
        
        // Membentuk struktur folder bersarang (Angkatan / Nama Mahasiswa)
        $folderName = "{$angkatan}/{$nama}";

        // 1. Coba upload ke Google Drive Keuangan
        try {
            $buktiPath = $request->file('bukti_tf')->store($folderName, 'google_keuangan');
            $buktiTfUrl = Storage::disk('google_keuangan')->url($buktiPath);
        } catch (\Exception $e) {
            Log::warning('Google Drive Keuangan upload failed, trying main Google Drive: ' . $e->getMessage());
            
            // 2. Fallback ke Google Drive Utama (kampus)
            try {
                $mainDrivePath = "Keuangan/{$folderName}/Bukti_TF_" . $request->file('bukti_tf')->hashName();
                Storage::disk('google')->put($mainDrivePath, file_get_contents($request->file('bukti_tf')->getRealPath()));
                $buktiTfUrl = Storage::disk('google')->url($mainDrivePath);
            } catch (\Exception $e2) {
                Log::error('Main Google Drive upload also failed, fallback to local storage: ' . $e2->getMessage());
                // 3. Fallback terakhir ke penyimpanan lokal
                $buktiPath = $request->file('bukti_tf')->store('pembayaran', 'public');
                $buktiTfUrl = $buktiPath;
            }
        }
        
        $berkasPendukungUrl = null;
        if ($request->hasFile('berkas_pendukung')) {
            try {
                $berkasPath = $request->file('berkas_pendukung')->store($folderName, 'google_keuangan');
                $berkasPendukungUrl = Storage::disk('google_keuangan')->url($berkasPath);
            } catch (\Exception $e) {
                try {
                    $mainDrivePath = "Keuangan/{$folderName}/Berkas_" . $request->file('berkas_pendukung')->hashName();
                    Storage::disk('google')->put($mainDrivePath, file_get_contents($request->file('berkas_pendukung')->getRealPath()));
                    $berkasPendukungUrl = Storage::disk('google')->url($mainDrivePath);
                } catch (\Exception $e2) {
                    $berkasPath = $request->file('berkas_pendukung')->store('pembayaran/pendukung', 'public');
                    $berkasPendukungUrl = $berkasPath;
                }
            }
        }

        if ($buktiTfUrl && preg_match('/id=([a-zA-Z0-9_-]+)/', $buktiTfUrl, $m)) {
            $buktiTfUrl = "https://drive.google.com/file/d/{$m[1]}/view";
        }
        if ($berkasPendukungUrl && preg_match('/id=([a-zA-Z0-9_-]+)/', $berkasPendukungUrl, $m)) {
            $berkasPendukungUrl = "https://drive.google.com/file/d/{$m[1]}/view";
        }

        // Deteksi bulan alokasi dari tagihan SPP jika ada
        $bulanAlokasi = null;
        if ($tagihan && stripos($tagihan->nama_tagihan, 'spp') !== false) {
            $monthNames = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
                'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
                'jun' => 6, 'jul' => 7, 'ags' => 8, 'sep' => 9,
                'okt' => 10, 'nov' => 11, 'des' => 12,
            ];
            $namaLower = strtolower($tagihan->nama_tagihan);
            $detectedM = null;
            foreach ($monthNames as $mKey => $mVal) {
                if (str_contains($namaLower, $mKey)) {
                    $detectedM = $mVal;
                    break;
                }
            }
            $year = date('Y');
            if ($tagihan->jatuh_tempo) {
                try {
                    $year = \Carbon\Carbon::parse($tagihan->jatuh_tempo)->year;
                } catch (\Exception $e) {}
            }
            if ($detectedM) {
                $bulanAlokasi = [sprintf('%04d-%02d', $year, $detectedM)];
            }
        }

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'user_id' => auth()->id(),
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'nominal' => $nominal,
            'bukti_tf' => $buktiTfUrl,
            'berkas_pendukung' => $berkasPendukungUrl,
            'catatan' => $request->catatan,
            'status' => 'pending',
            'bulan_alokasi' => $bulanAlokasi,
        ]);

        try {
            // Trigger notifikasi real-time untuk Admin Keuangan
            \App\Models\KeuanganNotification::sendSppPendingNotification($pembayaran, auth()->user());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create KeuanganNotification: ' . $e->getMessage());
        }

        try {
            // Get first finance admin email, fallback to a default email if none exists
            $adminKeuangan = \App\Models\User::where('role', 'keuangan')->first();
            $adminEmail = $adminKeuangan ? $adminKeuangan->email : 'finance@matla.id';
            
            Mail::to($adminEmail)->send(new \App\Mail\PaymentSubmittedMail($pembayaran, auth()->user()));
        } catch (\Exception $e) {
            Log::error('Failed to send payment submitted email: ' . $e->getMessage());
        }

        return redirect()->route('backend.mahasiswa.pembayaran.tagihan')->with('success', 'Konfirmasi pembayaran berhasil dikirim dan menunggu verifikasi Admin.');
    }

    public function riwayat()
    {
        // Get tagihans that have payments, ordered by newest payments
        $tagihans = Tagihan::where('user_id', auth()->id())
            ->whereHas('pembayarans')
            ->with(['pembayarans' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pembayaran.riwayat', compact('tagihans'));
    }

    public function kwitansi($id)
    {
        $pembayaran = Pembayaran::where('user_id', auth()->id())
            ->where('status', 'verified')
            ->whereNotNull('kwitansi')
            ->with(['tagihan', 'user'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('mahasiswa.pembayaran.kwitansi', compact('pembayaran'));
        $safeKwitansiNo = str_replace('/', '-', $pembayaran->kwitansi);
        return $pdf->download('Kwitansi_Pembayaran_' . $safeKwitansiNo . '.pdf');
    }

    /**
     * Sinkronisasi Status Tagihan SPP:
     * Menyesuaikan status tagihan SPP yang sudah ada jika terdapat pembayaran terverifikasi.
     * Tidak membuat tagihan baru secara otomatis agar tagihan yang telah dihapus oleh Admin Keuangan tidak terbit kembali.
     */
    protected function syncActiveSemesterTagihans($user)
    {
        try {
            $activeSemester = MasterSemester::with('tahunAkademik')->where('is_active', true)->first();
            if (!$activeSemester) {
                return;
            }

            $daftarBulan = $activeSemester->getDaftarBulan();
            $verifiedPayments = Pembayaran::where('user_id', $user->id)
                ->where('status', 'verified')
                ->get();

            $userTagihans = Tagihan::where('user_id', $user->id)->get();

            foreach ($daftarBulan as $b) {
                $monthKey = $b['key']; // 'YYYY-MM'
                $bNama = $b['nama'];   // 'Juli'
                $bNum = $b['num'];     // 7
                $bYear = $b['year'];   // 2026

                // Cek apakah bulan ini sudah lunas via pembayaran terverifikasi
                $isPaid = $verifiedPayments->first(function ($p) use ($monthKey, $bNum, $bNama) {
                    $alokasi = $p->bulan_alokasi;
                    if (!empty($alokasi) && is_array($alokasi)) {
                        if (in_array($monthKey, $alokasi) || in_array($bNum, $alokasi) || in_array((string)$bNum, $alokasi)) {
                            return true;
                        }
                        if (in_array(strtolower($bNama), array_map('strtolower', $alokasi))) {
                            return true;
                        }
                    }
                    return false;
                }) !== null;

                $existingTagihan = $userTagihans->first(function ($t) use ($bNum, $bYear, $bNama) {
                    return Tagihan::isMatchingSppMonth($t, $bNum, $bYear, $bNama);
                });

                if ($isPaid && $existingTagihan && $existingTagihan->status !== 'lunas') {
                    $existingTagihan->update([
                        'status' => 'lunas',
                        'sisa_tagihan' => 0,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal sinkronisasi status tagihan SPP: ' . $e->getMessage());
        }
    }
}
