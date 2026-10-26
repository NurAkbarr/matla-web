<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Exports\RekapSppSemesterExport;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapSppController extends Controller
{
    /**
     * Tampilkan halaman Rekap SPP per Semester
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $data = $this->prepareRekapData($request);

        return view('backend.keuangan.rekap-spp.index', $data);
    }

    /**
     * Export Rekap SPP ke Excel
     */
    public function exportExcel(Request $request)
    {
        $data = $this->prepareRekapData($request);
        $semesterLabel = strtoupper($data['tipeSemester']);
        $tahunSlug = str_replace('/', '-', $data['tahunAkademik']);
        $filename = "Rekap_SPP_Semester_{$semesterLabel}_{$tahunSlug}.xlsx";

        return Excel::download(new RekapSppSemesterExport($data), $filename);
    }

    /**
     * Export Rekap SPP ke PDF (Format Lanskap Kop Resmi MATLA)
     */
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');
        $data = $this->prepareRekapData($request);
        $semesterLabel = strtoupper($data['tipeSemester']);
        $tahunSlug = str_replace('/', '-', $data['tahunAkademik']);
        $filename = "Rekap_SPP_Semester_{$semesterLabel}_{$tahunSlug}.pdf";

        $pdf = Pdf::loadView('backend.keuangan.rekap-spp.export-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Siapkan seluruh data rekap matriks 6 bulan semester
     */
    private function prepareRekapData(Request $request): array
    {
        $now = Carbon::now();

        // 1. Tahun Akademik (misal 2026/2027)
        $currentYear = (int)$now->format('Y');
        $currentMonth = (int)$now->format('n');

        $defaultTahunAkademik = ($currentMonth >= 7)
            ? "{$currentYear}/" . ($currentYear + 1)
            : ($currentYear - 1) . "/{$currentYear}";

        $tahunAkademik = $request->get('tahun_akademik', $defaultTahunAkademik);
        $partsTahun = explode('/', $tahunAkademik);
        $startYear = isset($partsTahun[0]) && is_numeric($partsTahun[0]) ? (int)$partsTahun[0] : $currentYear;
        $endYear = isset($partsTahun[1]) && is_numeric($partsTahun[1]) ? (int)$partsTahun[1] : ($startYear + 1);

        // 2. Tipe Semester (Ganjil vs Genap)
        $defaultTipeSemester = ($currentMonth >= 7) ? 'ganjil' : 'genap';
        $tipeSemester = strtolower($request->get('tipe_semester', $defaultTipeSemester));
        if (!in_array($tipeSemester, ['ganjil', 'genap'])) {
            $tipeSemester = 'ganjil';
        }

        // 3. Konfigurasi 6 Bulan Semester
        if ($tipeSemester === 'ganjil') {
            $daftarBulan = [
                ['nama' => 'Juli', 'short' => 'Jul', 'num' => 7, 'year' => $startYear],
                ['nama' => 'Agustus', 'short' => 'Ags', 'num' => 8, 'year' => $startYear],
                ['nama' => 'September', 'short' => 'Sep', 'num' => 9, 'year' => $startYear],
                ['nama' => 'Oktober', 'short' => 'Okt', 'num' => 10, 'year' => $startYear],
                ['nama' => 'November', 'short' => 'Nov', 'num' => 11, 'year' => $startYear],
                ['nama' => 'Desember', 'short' => 'Des', 'num' => 12, 'year' => $startYear],
            ];
            $periodeLabel = "Semester Ganjil TA {$tahunAkademik} (Juli - Desember {$startYear})";
        } else {
            $daftarBulan = [
                ['nama' => 'Januari', 'short' => 'Jan', 'num' => 1, 'year' => $endYear],
                ['nama' => 'Februari', 'short' => 'Feb', 'num' => 2, 'year' => $endYear],
                ['nama' => 'Maret', 'short' => 'Mar', 'num' => 3, 'year' => $endYear],
                ['nama' => 'April', 'short' => 'Apr', 'num' => 4, 'year' => $endYear],
                ['nama' => 'Mei', 'short' => 'Mei', 'num' => 5, 'year' => $endYear],
                ['nama' => 'Juni', 'short' => 'Jun', 'num' => 6, 'year' => $endYear],
            ];
            $periodeLabel = "Semester Genap TA {$tahunAkademik} (Januari - Juni {$endYear})";
        }

        // 4. Query Mahasiswa dengan Filter
        $query = User::where('role', 'mahasiswa')
            ->with(['tagihans.pembayarans']);

        // Filter Status Akademik (Default: AKTIF)
        $statusAkademik = $request->get('status_akademik', 'AKTIF');
        if ($statusAkademik && strtoupper($statusAkademik) !== 'SEMUA') {
            $query->whereRaw("UPPER(COALESCE(status, 'AKTIF')) = ?", [strtoupper($statusAkademik)]);
        }

        // Filter Program Studi
        $selectedProdi = $request->get('prodi');
        if (!empty($selectedProdi) && $selectedProdi !== 'semua') {
            $query->where(function ($q) use ($selectedProdi) {
                $q->where('education->program_studi', $selectedProdi)
                  ->orWhere('prodi', $selectedProdi);
            });
        }

        // Filter Semester Mahasiswa (1-8)
        $selectedSemester = $request->get('semester');
        if (!empty($selectedSemester) && $selectedSemester !== 'semua') {
            $query->where('semester', (int)$selectedSemester);
        }

        // Filter Pencarian (Nama / NIM)
        $search = trim($request->get('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Ambil mahasiswa diurutkan berdasarkan NIM / Nama
        $mahasiswaList = $query->orderBy('nim')->orderBy('name')->get();

        // 5. Olah Matriks Rekapitulasi per Mahasiswa
        $rekapItems = [];
        $totalPotensiSemua = 0;
        $totalTerkumpulSemua = 0;
        $totalTunggakanSemua = 0;
        $totalMahasiswaLunas = 0;
        $totalMahasiswaMenunggak = 0;

        foreach ($mahasiswaList as $mhs) {
            $nominalSpp = (float)($mhs->nominal_spp ?? 300000);
            if ($nominalSpp <= 0) {
                $nominalSpp = 300000;
            }

            $bulanMatriks = [];
            $bulanLunasCount = 0;
            $totalSudahDibayarMhs = 0;
            $metodeTerakhir = 'Belum Ada Pembayaran';

            foreach ($daftarBulan as $bInfo) {
                $bNama = $bInfo['nama'];
                $bNum = $bInfo['num'];
                $bYear = $bInfo['year'];

                // Cari tagihan SPP untuk mahasiswa dan bulan ini
                $matchingTagihan = $mhs->tagihans->first(function ($t) use ($bNama, $bNum, $bYear) {
                    $namaLower = strtolower($t->nama_tagihan ?? '');
                    $bNamaLower = strtolower($bNama);
                    $bShortLower = strtolower(substr($bNama, 0, 3));

                    // Cek nama tagihan memuat nama bulan (contoh: SPP OKTOBER)
                    if (str_contains($namaLower, $bNamaLower) || str_contains($namaLower, $bShortLower)) {
                        return true;
                    }

                    // Cek tanggal jatuh tempo
                    if ($t->jatuh_tempo) {
                        try {
                            $jt = Carbon::parse($t->jatuh_tempo);
                            if ($jt->month === $bNum && $jt->year === $bYear) {
                                return true;
                            }
                        } catch (\Exception $e) {}
                    }

                    // Cek tanggal pembuatan tagihan jika tipe SPP
                    if ($t->created_at && (str_contains($namaLower, 'spp') || $t->jenis_tagihan === 'lunas')) {
                        if ($t->created_at->month === $bNum && $t->created_at->year === $bYear) {
                            return true;
                        }
                    }

                    return false;
                });

                if ($matchingTagihan) {
                    $isLunas = (
                        strtolower($matchingTagihan->status) === 'lunas' ||
                        (float)$matchingTagihan->sisa_tagihan <= 0
                    );

                    $verifiedSum = $matchingTagihan->pembayarans
                        ->where('status', 'verified')
                        ->sum('nominal');

                    if ($verifiedSum >= (float)$matchingTagihan->nominal_total && (float)$matchingTagihan->nominal_total > 0) {
                        $isLunas = true;
                    }

                    $nominalBayar = $isLunas ? (float)$matchingTagihan->nominal_total : (float)$verifiedSum;
                    $nominalTunggakan = $isLunas ? 0 : max(0, (float)$matchingTagihan->nominal_total - $nominalBayar);

                    // Update metode pembayaran terakhir
                    $lastVerifiedPay = $matchingTagihan->pembayarans->where('status', 'verified')->last();
                    if ($lastVerifiedPay) {
                        $metodeTerakhir = $lastVerifiedPay->jenis_pembayaran ?: 'Transfer Bank';
                    }

                    if ($isLunas) {
                        $bulanLunasCount++;
                    }

                    $totalSudahDibayarMhs += $nominalBayar;

                    $bulanMatriks[] = [
                        'nama' => $bNama,
                        'num' => $bNum,
                        'year' => $bYear,
                        'ada_tagihan' => true,
                        'is_lunas' => $isLunas,
                        'nominal_tagihan' => (float)$matchingTagihan->nominal_total,
                        'nominal_bayar' => $nominalBayar,
                        'nominal_tunggakan' => $nominalTunggakan,
                        'tagihan_id' => $matchingTagihan->id,
                    ];
                } else {
                    // Belum ada tagihan terdaftar untuk bulan ini
                    $bulanMatriks[] = [
                        'nama' => $bNama,
                        'num' => $bNum,
                        'year' => $bYear,
                        'ada_tagihan' => false,
                        'is_lunas' => false,
                        'nominal_tagihan' => $nominalSpp,
                        'nominal_bayar' => 0,
                        'nominal_tunggakan' => $nominalSpp,
                        'tagihan_id' => null,
                    ];
                }
            }

            $bulanMenunggakCount = 6 - $bulanLunasCount;
            $totalTagihanSemesterMhs = 6 * $nominalSpp;
            $sisaTunggakanMhs = max(0, $bulanMenunggakCount * $nominalSpp);

            if ($bulanMenunggakCount === 0) {
                $statusKelunasan = 'Lunas';
                $totalMahasiswaLunas++;
            } else {
                $statusKelunasan = 'Menunggak';
                $totalMahasiswaMenunggak++;
            }

            $totalPotensiSemua += $totalTagihanSemesterMhs;
            $totalTerkumpulSemua += $totalSudahDibayarMhs;
            $totalTunggakanSemua += $sisaTunggakanMhs;

            $prodiNama = $mhs->education['program_studi'] ?? ($mhs->prodi ?? '-');

            $rekapItems[] = [
                'id' => $mhs->id,
                'nim' => $mhs->nim ?: '-',
                'nama' => $mhs->name,
                'prodi' => $prodiNama,
                'semester_mhs' => $mhs->semester ?: 1,
                'status_akademik' => strtoupper($mhs->status ?? 'AKTIF'),
                'nominal_spp' => $nominalSpp,
                'matriks_bulan' => $bulanMatriks,
                'bulan_lunas' => $bulanLunasCount,
                'bulan_menunggak' => $bulanMenunggakCount,
                'total_tagihan' => $totalTagihanSemesterMhs,
                'total_dibayar' => $totalSudahDibayarMhs,
                'sisa_tunggakan' => $sisaTunggakanMhs,
                'status' => $statusKelunasan,
                'metode' => $metodeTerakhir,
            ];
        }

        // 6. Daftar Opsi Filter
        $allStudents = User::where('role', 'mahasiswa')->get();
        $daftarProdi = $allStudents->map(function ($u) {
            return $u->education['program_studi'] ?? ($u->prodi ?? null);
        })->filter()->unique()->values()->all();

        $daftarSemesterMhs = $allStudents->pluck('semester')->filter()->unique()->sort()->values()->all();
        if (empty($daftarSemesterMhs)) {
            $daftarSemesterMhs = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        $daftarTahunAkademik = [
            ($currentYear - 1) . "/{$currentYear}",
            "{$currentYear}/" . ($currentYear + 1),
            ($currentYear + 1) . "/" . ($currentYear + 2),
        ];

        $persentaseLunas = $totalPotensiSemua > 0
            ? round(($totalTerkumpulSemua / $totalPotensiSemua) * 100, 1)
            : 0;

        return [
            'rekapItems' => $rekapItems,
            'daftarBulan' => $daftarBulan,
            'periodeLabel' => $periodeLabel,
            'tipeSemester' => $tipeSemester,
            'tahunAkademik' => $tahunAkademik,
            'selectedProdi' => $selectedProdi,
            'selectedSemester' => $selectedSemester,
            'statusAkademik' => $statusAkademik,
            'search' => $search,
            'daftarProdi' => $daftarProdi,
            'daftarSemesterMhs' => $daftarSemesterMhs,
            'daftarTahunAkademik' => $daftarTahunAkademik,
            'summary' => [
                'total_mahasiswa' => count($rekapItems),
                'total_potensi' => $totalPotensiSemua,
                'total_terkumpul' => $totalTerkumpulSemua,
                'total_tunggakan' => $totalTunggakanSemua,
                'persentase_lunas' => $persentaseLunas,
                'mahasiswa_lunas' => $totalMahasiswaLunas,
                'mahasiswa_menunggak' => $totalMahasiswaMenunggak,
            ],
        ];
    }
}
