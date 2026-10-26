<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapSppSemesterExport implements FromView, ShouldAutoSize, WithStyles
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('backend.keuangan.rekap-spp.export-excel', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // 1. Header Tabel Utama berada di baris ke-5 dan ke-6 (atau ke-6 jika ada sub-header)
        // Set warna hijau terang #00FF00 (ARGB: FF00FF00), tulisan hitam tebal
        $headerRange = 'A5:' . $highestCol . '5';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF00FF00'], // Hijau Terang #00FF00
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // 2. Berikan border hitam tipis ke seluruh sel tabel dari baris 5 hingga baris data terakhir
        $tableRange = 'A5:' . $highestCol . $highestRow;
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Hitam tipis
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 3. Format khusus pada judul dokumen (Baris 1 - 3)
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF00703C');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->getColor()->setARGB('FF555555');

        // 4. Alignment tengah untuk kolom No, NIM, Matriks Bulan (C-H), Bulan Menunggak, Status
        for ($r = 6; $r <= $highestRow; $r++) {
            $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Kolom bulan (D sampai I)
            $sheet->getStyle("D{$r}:I{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Bulan menunggak (K)
            $sheet->getStyle("K{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Status (O)
            $sheet->getStyle("O{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
}
