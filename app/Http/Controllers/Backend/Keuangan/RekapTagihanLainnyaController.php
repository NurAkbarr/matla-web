<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapTagihanLainnyaController extends Controller
{
    /**
     * Tampilkan halaman Rekap Tagihan Lainnya (Non-SPP: Wisuda, Skripsi, Pengindukan, dll.)
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $data = $this->prepareData($request);

        // Paginate query
        $perPage = (int) $request->get('per_page', 25);
        if (!in_array($perPage, [10, 15, 25, 50, 100])) {
            $perPage = 25;
        }

        $tagihans = $data['query']
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('backend.keuangan.rekap-tagihan-lainnya.index', [
            'tagihans' => $tagihans,
            'daftarJenisTagihan' => $data['daftarJenisTagihan'],
            'daftarProdi' => $data['daftarProdi'],
            'totalNominal' => $data['totalNominal'],
            'totalDibayar' => $data['totalDibayar'],
            'totalSisa' => $data['totalSisa'],
            'countLunas' => $data['countLunas'],
            'countSebagian' => $data['countSebagian'],
            'countBelumLunas' => $data['countBelumLunas'],
            'totalTagihan' => $data['totalTagihan'],
            'selectedJenis' => $request->get('jenis'),
            'selectedProdi' => $request->get('prodi'),
            'selectedStatus' => $request->get('status'),
            'search' => $request->get('search'),
        ]);
    }

    /**
     * Export data ke Excel
     */
    public function exportExcel(Request $request)
    {
        Carbon::setLocale('id');
        $data = $this->prepareData($request);
        $items = $data['query']->latest()->get();

        $filename = "Rekap_Tagihan_Lainnya_MATLA_" . date('Ymd_His') . ".xls";

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; }';
        $html .= 'th { background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000; padding: 8px; }';
        $html .= 'td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.lunas { background-color: #E8F5E9; color: #1B5E20; font-weight: bold; }';
        $html .= '.sebagian { background-color: #FFF8E1; color: #F57F17; font-weight: bold; }';
        $html .= '.belum-lunas { background-color: #FFEBEE; color: #B71C1C; font-weight: bold; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';

        $html .= '<h3 style="margin-bottom: 4px;">UNIVERSITAS MATLAUL ANWAR BANTEN (MATLA UNIVERSITY)</h3>';
        $html .= '<h2 style="margin-top: 0; margin-bottom: 6px;">REKAPITULASI TAGIHAN LAINNYA (NON-SPP)</h2>';
        $html .= '<p style="font-size: 9pt; color: #555; margin-top: 0; margin-bottom: 14px;">Tanggal Unduh: ' . Carbon::now()->translatedFormat('d F Y, H:i') . ' WIB</p>';

        $html .= '<table border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width: 40px;">No</th>';
        $html .= '<th>NIM</th>';
        $html .= '<th>Nama Mahasiswa</th>';
        $html .= '<th>Program Studi</th>';
        $html .= '<th>Angkatan</th>';
        $html .= '<th>Item Tagihan</th>';
        $html .= '<th>Keterangan / Jatuh Tempo</th>';
        $html .= '<th>Nominal Total (Rp)</th>';
        $html .= '<th>Dibayar (Rp)</th>';
        $html .= '<th>Sisa Kekurangan (Rp)</th>';
        $html .= '<th>Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no = 1;
        $sumTotal = 0;
        $sumDibayar = 0;
        $sumSisa = 0;

        foreach ($items as $t) {
            $nomTotal = (float) $t->nominal_total;
            $sisa = (float) $t->sisa_tagihan;
            $dibayar = max(0, $nomTotal - $sisa);

            $sumTotal += $nomTotal;
            $sumDibayar += $dibayar;
            $sumSisa += $sisa;

            $statusText = 'Belum Lunas';
            $statusClass = 'belum-lunas';
            if ($sisa <= 0 || strtolower($t->status) === 'lunas') {
                $statusText = 'Lunas';
                $statusClass = 'lunas';
            } elseif ($dibayar > 0) {
                $statusText = 'Cicilan Sebagian';
                $statusClass = 'sebagian';
            }

            $tempoStr = $t->jatuh_tempo ? Carbon::parse($t->jatuh_tempo)->format('d/m/Y') : '-';
            $ket = trim($t->keterangan ?? '');
            $ketFull = $tempoStr !== '-' ? "Tempo: {$tempoStr}" . ($ket ? " ({$ket})" : '') : ($ket ?: '-');

            $html .= '<tr>';
            $html .= '<td class="text-center">' . $no++ . '</td>';
            $html .= '<td class="text-center">' . ($t->user->nim ?? '-') . '</td>';
            $html .= '<td>' . ($t->user->name ?? '-') . '</td>';
            $html .= '<td>' . ($t->user->education['program_studi'] ?? '-') . '</td>';
            $html .= '<td class="text-center">' . ($t->user->angkatan ?? '-') . '</td>';
            $html .= '<td><b>' . htmlspecialchars($t->nama_tagihan) . '</b></td>';
            $html .= '<td>' . htmlspecialchars($ketFull) . '</td>';
            $html .= '<td class="text-right">' . number_format($nomTotal, 0, ',', '.') . '</td>';
            $html .= '<td class="text-right">' . number_format($dibayar, 0, ',', '.') . '</td>';
            $html .= '<td class="text-right">' . number_format($sisa, 0, ',', '.') . '</td>';
            $html .= '<td class="text-center ' . $statusClass . '">' . $statusText . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '<tfoot>';
        $html .= '<tr style="background-color: #F4F6F8; font-weight: bold;">';
        $html .= '<td colspan="7" class="text-center">TOTAL REKAPITULASI</td>';
        $html .= '<td class="text-right">' . number_format($sumTotal, 0, ',', '.') . '</td>';
        $html .= '<td class="text-right">' . number_format($sumDibayar, 0, ',', '.') . '</td>';
        $html .= '<td class="text-right">' . number_format($sumSisa, 0, ',', '.') . '</td>';
        $html .= '<td class="text-center">' . count($items) . ' Tagihan</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        $html .= '</table>';
        $html .= '</body></html>';

        return response($html, 200, $headers);
    }

    /**
     * Export data ke PDF Resmi
     */
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');
        $data = $this->prepareData($request);
        $items = $data['query']->latest()->get();

        $pdf = Pdf::loadView('backend.keuangan.rekap-tagihan-lainnya.export-pdf', [
            'items' => $items,
            'totalNominal' => $data['totalNominal'],
            'totalDibayar' => $data['totalDibayar'],
            'totalSisa' => $data['totalSisa'],
            'countLunas' => $data['countLunas'],
            'countSebagian' => $data['countSebagian'],
            'countBelumLunas' => $data['countBelumLunas'],
            'selectedJenis' => $request->get('jenis'),
            'selectedProdi' => $request->get('prodi'),
            'selectedStatus' => $request->get('status'),
            'filterLabel' => $this->getFilterLabel($request),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Rekap_Tagihan_Lainnya_MATLA_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Query builder helper & summary statistics
     */
    private function prepareData(Request $request): array
    {
        $query = Tagihan::nonSpp()->with(['user', 'pembayarans' => fn($q) => $q->where('status', 'verified')]);

        // Filter Jenis / Item Tagihan
        if ($request->filled('jenis') && $request->get('jenis') !== 'semua') {
            $jenis = trim($request->get('jenis'));
            $query->where('nama_tagihan', 'like', "%{$jenis}%");
        }

        // Filter Program Studi
        if ($request->filled('prodi') && $request->get('prodi') !== 'semua') {
            $prodi = trim($request->get('prodi'));
            $query->whereHas('user', function ($uq) use ($prodi) {
                $uq->where('education->program_studi', $prodi);
            });
        }

        // Filter Status Kelunasan
        if ($request->filled('status') && $request->get('status') !== 'semua') {
            $status = strtolower($request->get('status'));
            if ($status === 'lunas') {
                $query->where(function ($q) {
                    $q->where('status', 'lunas')
                      ->orWhere('sisa_tagihan', '<=', 0);
                });
            } elseif ($status === 'sebagian' || $status === 'cicilan') {
                $query->where(function ($q) {
                    $q->where('status', '!=', 'lunas')
                      ->where('sisa_tagihan', '>', 0)
                      ->whereColumn('sisa_tagihan', '<', 'nominal_total');
                });
            } elseif ($status === 'belum_lunas') {
                $query->where(function ($q) {
                    $q->where('status', '!=', 'lunas')
                      ->where(function ($sq) {
                          $sq->whereColumn('sisa_tagihan', '>=', 'nominal_total')
                             ->orWhereNull('sisa_tagihan');
                      });
                });
            }
        }

        // Filter Pencarian (Nama, NIM, Nama Tagihan, Keterangan)
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama_tagihan', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        // Distinct list for filters
        $daftarJenisTagihan = Tagihan::nonSpp()
            ->select('nama_tagihan')
            ->distinct()
            ->orderBy('nama_tagihan')
            ->pluck('nama_tagihan')
            ->all();

        $daftarProdi = User::where('role', 'mahasiswa')
            ->get()
            ->map(fn($u) => $u->education['program_studi'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        // Calculate summary metrics on cloned query
        $allFiltered = (clone $query)->get();

        $totalNominal = 0;
        $totalDibayar = 0;
        $totalSisa = 0;
        $countLunas = 0;
        $countSebagian = 0;
        $countBelumLunas = 0;

        foreach ($allFiltered as $t) {
            $nom = (float)$t->nominal_total;
            $sisa = (float)$t->sisa_tagihan;
            $bayar = max(0, $nom - $sisa);

            $totalNominal += $nom;
            $totalDibayar += $bayar;
            $totalSisa += $sisa;

            if ($sisa <= 0 || strtolower($t->status) === 'lunas') {
                $countLunas++;
            } elseif ($bayar > 0) {
                $countSebagian++;
            } else {
                $countBelumLunas++;
            }
        }

        return [
            'query' => $query,
            'daftarJenisTagihan' => $daftarJenisTagihan,
            'daftarProdi' => $daftarProdi,
            'totalNominal' => $totalNominal,
            'totalDibayar' => $totalDibayar,
            'totalSisa' => $totalSisa,
            'countLunas' => $countLunas,
            'countSebagian' => $countSebagian,
            'countBelumLunas' => $countBelumLunas,
            'totalTagihan' => $allFiltered->count(),
        ];
    }

    private function getFilterLabel(Request $request): string
    {
        $parts = [];
        if ($request->filled('jenis') && $request->get('jenis') !== 'semua') {
            $parts[] = "Jenis: " . $request->get('jenis');
        }
        if ($request->filled('prodi') && $request->get('prodi') !== 'semua') {
            $parts[] = "Prodi: " . $request->get('prodi');
        }
        if ($request->filled('status') && $request->get('status') !== 'semua') {
            $parts[] = "Status: " . ucfirst($request->get('status'));
        }
        return empty($parts) ? "Semua Tagihan Lainnya" : implode(' | ', $parts);
    }
}
