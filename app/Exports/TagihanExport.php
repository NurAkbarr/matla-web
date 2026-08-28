<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TagihanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Tagihan::with('user')->get()->sortBy(function($tagihan) {
            return $tagihan->user->nim ?? '';
        });
    }

    public function headings(): array
    {
        return [
            'Username',
            'Nama Lengkap',
            'Item Tagihan',
            'Nominal',
            'Status',
        ];
    }

    public function map($tagihan): array
    {
        return [
            $tagihan->user->nim ?? '-',
            $tagihan->user->name ?? 'Unknown',
            $tagihan->nama_tagihan,
            $tagihan->nominal_total,
            strtoupper($tagihan->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00703C']
                ]
            ],
        ];
    }
}
