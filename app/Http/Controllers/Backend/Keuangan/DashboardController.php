<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $now = Carbon::now();

        // -----------------------------------------------------------------
        // 1. TOP CARDS (ROW 1 - 3 Kolom Sejajar)
        // -----------------------------------------------------------------
        // A. Saldo Akun / Saldo Utama: Total saldo bersih keseluruhan yayasan/kampus
        $totalPengeluaranAllTime = 0; // Default Rp 0 jika belum ada tabel pengeluaran
        $saldoAkun = (float) Pembayaran::where('status', 'verified')->sum('nominal') - $totalPengeluaranAllTime;
        $saldoAkun = max(0, $saldoAkun);

        // B. Total Tagihan: Akumulasi tagihan mahasiswa AKTIF yang belum lunas (bulanan & semesteran)
        $tagihanBelumLunasQuery = Tagihan::where('status', '!=', 'lunas')
            ->whereHas('user', function($u) {
                $u->where('role', 'mahasiswa')
                  ->where(function($q) {
                      $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
                  });
            });
        $totalTagihan = (float) $tagihanBelumLunasQuery->sum('sisa_tagihan');
        $jumlahTagihan = $tagihanBelumLunasQuery->count();

        // C. Riwayat Transaksi: Total seluruh transaksi tercatat di sistem
        $totalTransaksi = Pembayaran::count();

        // -----------------------------------------------------------------
        // 2. DYNAMIC MONTH FILTER (Laporan Keuangan Bulanan)
        // -----------------------------------------------------------------
        // Generate list of months dynamically from earliest transaction (or at least past 12 months) up to now()
        $earliestCreated = Pembayaran::min('created_at') ?? Tagihan::min('created_at');
        $startRange = $earliestCreated ? Carbon::parse($earliestCreated)->startOfMonth() : $now->copy()->subMonths(11)->startOfMonth();

        // Pastikan minimal 12 bulan terakhir tersedia di dropdown
        $minMonths = $now->copy()->subMonths(11)->startOfMonth();
        if ($startRange->greaterThan($minMonths)) {
            $startRange = $minMonths;
        }

        $daftarBulan = [];
        $cursor = $now->copy()->startOfMonth();
        while ($cursor->greaterThanOrEqualTo($startRange)) {
            $daftarBulan[] = [
                'value' => $cursor->format('Y-m'),
                'bulan' => (int) $cursor->format('m'),
                'tahun' => (int) $cursor->format('Y'),
                'label' => $cursor->translatedFormat('F Y'),
            ];
            $cursor->subMonth();
        }

        // Tentukan periode terpilih (mendukung ?periode=YYYY-MM maupun ?bulan=MM&tahun=YYYY)
        $selectedPeriode = $request->query('periode');
        if (!$selectedPeriode && $request->filled('bulan') && $request->filled('tahun')) {
            $selectedPeriode = sprintf('%04d-%02d', (int) $request->query('tahun'), (int) $request->query('bulan'));
        }

        if (!$selectedPeriode || !preg_match('/^\d{4}-\d{2}$/', $selectedPeriode)) {
            $selectedPeriode = $now->format('Y-m');
        }

        // Jika periode pilihan di luar rentang default daftarBulan, tambahkan secara dinamis
        $existsInList = collect($daftarBulan)->contains('value', $selectedPeriode);
        if (!$existsInList) {
            try {
                $customCarbon = Carbon::createFromFormat('Y-m', $selectedPeriode)->startOfMonth();
                $daftarBulan[] = [
                    'value' => $customCarbon->format('Y-m'),
                    'bulan' => (int) $customCarbon->format('m'),
                    'tahun' => (int) $customCarbon->format('Y'),
                    'label' => $customCarbon->translatedFormat('F Y'),
                ];
                // Urutkan kembali descending
                usort($daftarBulan, fn($a, $b) => strcmp($b['value'], $a['value']));
            } catch (\Exception $e) {
                $selectedPeriode = $now->format('Y-m');
            }
        }

        $selectedCarbon = Carbon::createFromFormat('Y-m', $selectedPeriode)->startOfMonth();
        $selectedLabel = $selectedCarbon->translatedFormat('F Y');
        $startOfSelectedMonth = $selectedCarbon->copy()->startOfMonth();
        $endOfSelectedMonth = $selectedCarbon->copy()->endOfMonth();

        // -----------------------------------------------------------------
        // 3. LAPORAN KEUANGAN BULANAN - BARIS 1 (4 KOLOM)
        // -----------------------------------------------------------------
        // Transaksi pembayaran terverifikasi pada bulan yang dipilih
        $pembayaranBulanIni = Pembayaran::where('status', 'verified')
            ->whereBetween('updated_at', [$startOfSelectedMonth, $endOfSelectedMonth]);

        // 1. Total Pemasukan SPP
        $pemasukanSpp = (float) (clone $pembayaranBulanIni)
            ->where(function($q) {
                $q->whereHas('tagihan', function($t) {
                    $t->whereRaw('LOWER(nama_tagihan) LIKE ?', ['%spp%']);
                })->orWhereRaw('LOWER(jenis_pembayaran) LIKE ?', ['%spp%']);
            })
            ->sum('nominal');

        // 2. Total Pemasukan Saldo Lain (termasuk saldo bulan sebelumnya)
        // A. Pemasukan non-SPP pada bulan tersebut
        $pemasukanNonSppBulanIni = (float) (clone $pembayaranBulanIni)
            ->where(function($q) {
                $q->whereDoesntHave('tagihan', function($t) {
                    $t->whereRaw('LOWER(nama_tagihan) LIKE ?', ['%spp%']);
                })->whereRaw('LOWER(jenis_pembayaran) NOT LIKE ?', ['%spp%']);
            })
            ->sum('nominal');

        // B. Sisa saldo akumulasi sebelum bulan yang dipilih
        $pengeluaranSebelumnya = 0;
        $saldoSebelumnya = (float) Pembayaran::where('status', 'verified')
            ->where('updated_at', '<', $startOfSelectedMonth)
            ->sum('nominal') - $pengeluaranSebelumnya;
        $saldoSebelumnya = max(0, $saldoSebelumnya);

        $pemasukanLain = $pemasukanNonSppBulanIni + $saldoSebelumnya;

        // 3. Total Pengeluaran pada bulan yang dipilih (default 0 jika belum ada tabelnya)
        $totalPengeluaranBulanIni = 0;

        // 4. Total Tunggakan SPP (Saldo Minus) pada bulan tersebut (Hanya mahasiswa AKTIF)
        $tunggakanSpp = (float) Tagihan::where('status', '!=', 'lunas')
            ->whereHas('user', function($u) {
                $u->where('role', 'mahasiswa')
                  ->where(function($q) {
                      $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
                  });
            })
            ->whereRaw('LOWER(nama_tagihan) LIKE ?', ['%spp%'])
            ->where(function($q) use ($startOfSelectedMonth, $endOfSelectedMonth) {
                $q->whereBetween('jatuh_tempo', [$startOfSelectedMonth, $endOfSelectedMonth])
                  ->orWhere(function($sub) use ($startOfSelectedMonth, $endOfSelectedMonth) {
                      $sub->whereNull('jatuh_tempo')
                          ->whereBetween('created_at', [$startOfSelectedMonth, $endOfSelectedMonth]);
                  });
            })
            ->sum('sisa_tagihan');

        // -----------------------------------------------------------------
        // 4. LAPORAN KEUANGAN BULANAN - BARIS 2 (TOTAL KESELURUHAN)
        // -----------------------------------------------------------------
        // Rumus: (Total Pemasukan SPP + Total Pemasukan Saldo Lain) - Total Pengeluaran
        $totalKeseluruhan = ($pemasukanSpp + $pemasukanLain) - $totalPengeluaranBulanIni;

        // -----------------------------------------------------------------
        // 5. LAPORAN KEUANGAN BULANAN - BARIS 3 (STATISTIK MAHASISWA - 3 KOLOM)
        // -----------------------------------------------------------------
        // 1. Jumlah Seluruh Mahasiswa Aktif
        $totalMahasiswaAktif = User::where('role', 'mahasiswa')
            ->where(function($q) {
                $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
            })
            ->count();

        // 2. Mahasiswa Menunggak (memiliki tagihan belum lunas pada periode bulan tersebut, hanya mahasiswa AKTIF)
        $tagihanBulanTerpilih = Tagihan::whereHas('user', function($u) {
                $u->where('role', 'mahasiswa')
                  ->where(function($q) {
                      $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
                  });
            })
            ->where(function($q) use ($startOfSelectedMonth, $endOfSelectedMonth) {
                $q->whereBetween('jatuh_tempo', [$startOfSelectedMonth, $endOfSelectedMonth])
                  ->orWhere(function($sub) use ($startOfSelectedMonth, $endOfSelectedMonth) {
                      $sub->whereNull('jatuh_tempo')
                          ->whereBetween('created_at', [$startOfSelectedMonth, $endOfSelectedMonth]);
                  });
            });

        $mahasiswaMenunggak = (clone $tagihanBulanTerpilih)
            ->where('status', '!=', 'lunas')
            ->distinct('user_id')
            ->count('user_id');

        // 3. Mahasiswa Lunas pada periode bulan tersebut
        $mahasiswaLunas = max(0, $totalMahasiswaAktif - $mahasiswaMenunggak);

        // -----------------------------------------------------------------
        // 6. RIWAYAT TRANSAKSI TERKINI
        // -----------------------------------------------------------------
        $riwayatTerkini = Pembayaran::with(['user', 'tagihan'])
            ->has('tagihan')
            ->where('status', 'verified')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('backend.keuangan.dashboard', compact(
            'saldoAkun',
            'totalTagihan',
            'jumlahTagihan',
            'totalTransaksi',
            'daftarBulan',
            'selectedPeriode',
            'selectedLabel',
            'pemasukanSpp',
            'pemasukanLain',
            'saldoSebelumnya',
            'pemasukanNonSppBulanIni',
            'totalPengeluaranBulanIni',
            'tunggakanSpp',
            'totalKeseluruhan',
            'totalMahasiswaAktif',
            'mahasiswaMenunggak',
            'mahasiswaLunas',
            'riwayatTerkini'
        ));
    }
}
