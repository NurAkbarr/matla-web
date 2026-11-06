<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapSppSemesterExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Rekap SPP Semester';
    }

    public function array(): array
    {
        $periodeLabel = strtoupper($this->data['periodeLabel'] ?? 'SEMESTER');
        $selectedProdi = $this->data['selectedProdi'] ?: 'Semua Prodi';
        $selectedSemester = $this->data['selectedSemester'] ?: 'Semua';
        $statusAkademik = $this->data['statusAkademik'] ?? 'Semua Status';
        $daftarBulan = $this->data['daftarBulan'] ?? [];
        $rekapItems = $this->data['rekapItems'] ?? [];
        $summary = $this->data['summary'] ?? [
            'total_terkumpul' => 0,
            'total_tunggakan' => 0,
            'persentase_lunas' => 0,
        ];

        $printedAt = Carbon::now()->translatedFormat('l, d F Y H:i');

        $rows = [];

        // Baris 1: Judul Utama
        $rows[] = ["KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)"];
        // Baris 2: Sub-judul
        $rows[] = ["LEMBAR KERJA REKAPITULASI PEMBAYARAN SPP PER SEMESTER - {$periodeLabel}"];
        // Baris 3: Meta Info
        $rows[] = ["Dicetak pada: {$printedAt} | Program Studi: {$selectedProdi} | Semester Mhs: {$selectedSemester} | Status: {$statusAkademik}"];
        // Baris 4: Blank Spacer
        $rows[] = [];

        // Baris 5: Table Header (16 Kolom)
        $header = ['NO', 'NIM', 'NAMA MAHASISWA & PRODI'];
        foreach ($daftarBulan as $b) {
            $header[] = strtoupper($b['nama'] ?? 'BULAN');
        }
        $header[] = 'TARIF SPP (RP)';
        $header[] = 'TGK';
        $header[] = 'TAGIHAN (RP)';
        $header[] = 'DIBAYAR (RP)';
        $header[] = 'SISA (RP)';
        $header[] = 'STATUS';
        $header[] = 'KETERANGAN';

        $rows[] = $header;

        // Data Rows
        $totalTgk = 0;
        $totalTagihan = 0;

        foreach ($rekapItems as $index => $item) {
            $row = [
                $index + 1,
                (string) ($item['nim'] ?? '-'),
                ($item['nama'] ?? '') . ' (' . ($item['prodi'] ?? '-') . ' - Smst ' . ($item['semester_mhs'] ?? '-') . ')',
            ];

            // 6 Kolom Matriks Bulan (D - I)
            if (!empty($item['matriks_bulan']) && is_array($item['matriks_bulan'])) {
                foreach ($item['matriks_bulan'] as $m) {
                    if (!empty($m['is_lunas'])) {
                        $row[] = 'LUNAS';
                    } elseif (!empty($m['is_sebagian'])) {
                        $nominal = number_format((int)($m['nominal_bayar'] ?? 0), 0, ',', '.');
                        $row[] = "SEBAGIAN ({$nominal})";
                    } else {
                        $row[] = '-';
                    }
                }
            } else {
                for ($k = 0; $k < count($daftarBulan); $k++) {
                    $row[] = '-';
                }
            }

            $nominalSpp = (int) ($item['nominal_spp'] ?? 0);
            $bulanMenunggak = (int) ($item['bulan_menunggak'] ?? 0);
            $tagihan = (int) ($item['tagihan'] ?? 0);
            $totalDibayar = (int) ($item['total_dibayar'] ?? 0);
            $sisaTunggakan = (int) ($item['sisa_tunggakan'] ?? 0);

            $totalTgk += $bulanMenunggak;
            $totalTagihan += $tagihan;

            $row[] = $nominalSpp;
            $row[] = $bulanMenunggak;
            $row[] = $tagihan;
            $row[] = $totalDibayar;
            $row[] = $sisaTunggakan;
            $row[] = strtoupper((string) ($item['status'] ?? 'BELUM LUNAS'));
            $row[] = (string) ($item['metode'] ?? '-');

            $rows[] = $row;
        }

        if (empty($rekapItems)) {
            $emptyRow = array_fill(0, count($header), '');
            $emptyRow[0] = 'Tidak ada data mahasiswa yang cocok dengan kriteria filter.';
            $rows[] = $emptyRow;
        }

        // Baris Total Keseluruhan
        $totalMahasiswa = count($rekapItems);
        $totalCols = count($header);
        $footer = array_fill(0, $totalCols, '');
        $footer[0] = "TOTAL KESELURUHAN ({$totalMahasiswa} Mahasiswa):";
        $footer[9] = '-'; // Tarif SPP
        $footer[10] = $totalTgk;
        $footer[11] = $totalTagihan;
        $footer[12] = (int) ($summary['total_terkumpul'] ?? 0);
        $footer[13] = (int) ($summary['total_tunggakan'] ?? 0);
        $footer[14] = ($summary['persentase_lunas'] ?? 0) . '% Terkumpul';

        $rows[] = $footer;

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // 1. Format Judul Dokumen (Baris 1 - 3)
        $sheet->mergeCells("A1:{$highestCol}1");
        $sheet->mergeCells("A2:{$highestCol}2");
        $sheet->mergeCells("A3:{$highestCol}3");

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF00703C');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('FF000000');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->getColor()->setARGB('FF555555');

        // 2. Format Header Tabel (Baris 5) - Hijau MATLA
        $headerRange = "A5:{$highestCol}5";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF00703C'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(28);

        // 3. Border Tipis ke Seluruh Tabel (Baris 5 s.d. Baris Terakhir)
        $tableRange = "A5:{$highestCol}{$highestRow}";
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 4. Alignment & Format Angka per Kolom (Baris 6 s.d. Baris Sebelum Footer)
        for ($r = 6; $r < $highestRow; $r++) {
            $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$r}:I{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("O{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Format Number Currency (Kolom J, L, M, N)
            $sheet->getStyle("J{$r}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("L{$r}:N{$r}")->getNumberFormat()->setFormatCode('#,##0');
        }

        // 5. Format Footer Total (Baris Terakhir)
        $sheet->mergeCells("A{$highestRow}:I{$highestRow}");
        $footerRange = "A{$highestRow}:{$highestCol}{$highestRow}";
        $sheet->getStyle($footerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE2E8F0'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getStyle("A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("L{$highestRow}:N{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("O{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($highestRow)->setRowHeight(24);

        return [];
    }
}
