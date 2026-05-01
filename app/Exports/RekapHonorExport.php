<?php

namespace App\Exports;

use App\Models\Jadwal;
use App\Models\PresensiDosen;
use App\Models\User;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RekapHonorExport implements WithMultipleSheets
{
    protected $bulan;

    public function __construct(string $bulan)
    {
        $this->bulan = $bulan;
    }

    public function sheets(): array
    {
        return [
            new RekapHonorRingkasanSheet($this->bulan),
            new RekapHonorDetailSheet($this->bulan),
        ];
    }
}

// ============================================================
// SHEET 1: Ringkasan Per Dosen
// ============================================================
class RekapHonorRingkasanSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    protected $bulan;

    public function __construct(string $bulan)
    {
        $this->bulan = $bulan;
    }

    public function title(): string
    {
        return 'Ringkasan Honor';
    }

    public function array(): array
    {
        $honorPerPertemuan = Setting::get_value('honor_per_pertemuan', 75000);
        $bulanLabel = \Carbon\Carbon::parse($this->bulan . '-01')->translatedFormat('F Y');

        $rows = [];

        // Title rows
        $rows[] = ['REKAP HONOR DOSEN - ' . strtoupper($bulanLabel)];
        $rows[] = ['Tarif per Pertemuan: Rp ' . number_format($honorPerPertemuan, 0, ',', '.')];
        $rows[] = ['Dicetak pada: ' . now()->format('d/m/Y H:i')];
        $rows[] = []; // blank

        // Header
        $rows[] = ['No', 'Nama Dosen', 'Jumlah Kelas', 'Total Pertemuan Hadir', 'Total Izin', 'Total Sakit', 'Total Alfa', 'Total Honor'];

        $dosens = User::where('role', 'dosen')
            ->with(['presensiDosens' => fn($q) => $q->where('bulan', $this->bulan)])
            ->get();

        $grandTotal = 0;
        $no = 1;
        foreach ($dosens as $dosen) {
            $hadir = 0; $izin = 0; $sakit = 0; $alfa = 0;
            foreach ($dosen->presensiDosens as $p) {
                foreach (['pekan_1', 'pekan_2', 'pekan_3', 'pekan_4'] as $pk) {
                    if ($p->$pk === 'Hadir') $hadir++;
                    elseif ($p->$pk === 'Izin') $izin++;
                    elseif ($p->$pk === 'Sakit') $sakit++;
                    elseif ($p->$pk === 'Alfa') $alfa++;
                }
            }
            $totalHonor = $hadir * $honorPerPertemuan;
            $grandTotal += $totalHonor;

            $rows[] = [
                $no++,
                $dosen->name,
                $dosen->presensiDosens->count(),
                $hadir,
                $izin,
                $sakit,
                $alfa,
                $totalHonor,
            ];
        }

        $rows[] = []; // blank
        $rows[] = ['', '', '', '', '', '', 'GRAND TOTAL:', $grandTotal];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:H1');

        // Header row (row 5)
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Grand total row
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$lastRow}:H{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']],
        ]);

        // Currency format for Total Honor column H
        $sheet->getStyle('H6:H' . $lastRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');

        return [];
    }
}

// ============================================================
// SHEET 2: Detail Per Kelas Per Dosen
// ============================================================
class RekapHonorDetailSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    protected $bulan;

    public function __construct(string $bulan)
    {
        $this->bulan = $bulan;
    }

    public function title(): string
    {
        return 'Detail Per Kelas';
    }

    public function array(): array
    {
        $honorPerPertemuan = Setting::get_value('honor_per_pertemuan', 75000);
        $bulanLabel = \Carbon\Carbon::parse($this->bulan . '-01')->translatedFormat('F Y');

        $rows = [];

        // Title
        $rows[] = ['REKAP DETAIL KEHADIRAN DOSEN - ' . strtoupper($bulanLabel)];
        $rows[] = [];

        // Header
        $rows[] = [
            'No',
            'Nama Dosen',
            'Mata Kuliah',
            'Semester',
            'Angkatan',
            'Pertemuan 1',
            'Pertemuan 2',
            'Pertemuan 3',
            'Pertemuan 4',
            'Jml Pertemuan Hadir',
            'Fee/Pertemuan',
            'Total Honor',
        ];

        $dosens = User::where('role', 'dosen')
            ->with(['presensiDosens' => fn($q) => $q->where('bulan', $this->bulan)->orderBy('semester')])
            ->get();

        $no = 1;
        foreach ($dosens as $dosen) {
            foreach ($dosen->presensiDosens as $p) {
                $hadir = 0;
                if ($p->pekan_1 === 'Hadir') $hadir++;
                if ($p->pekan_2 === 'Hadir') $hadir++;
                if ($p->pekan_3 === 'Hadir') $hadir++;
                if ($p->pekan_4 === 'Hadir') $hadir++;

                $rows[] = [
                    $no++,
                    $dosen->name,
                    $p->mata_kuliah,
                    'Semester ' . $p->semester,
                    'Angkatan ' . $p->angkatan,
                    $p->pekan_1 ?? '-',
                    $p->pekan_2 ?? '-',
                    $p->pekan_3 ?? '-',
                    $p->pekan_4 ?? '-',
                    $hadir,
                    $honorPerPertemuan,
                    $hadir * $honorPerPertemuan,
                ];
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:L1');

        // Header row (row 3)
        $sheet->getStyle('A3:L3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Color-code attendance cells (columns F-I: Pertemuan 1-4)
        $lastRow = $sheet->getHighestRow();
        for ($row = 4; $row <= $lastRow; $row++) {
            foreach (['F', 'G', 'H', 'I'] as $col) {
                $val = $sheet->getCell($col . $row)->getValue();
                $color = match($val) {
                    'Hadir' => 'D1FAE5',
                    'Izin'  => 'DBEAFE',
                    'Sakit' => 'FEF3C7',
                    'Alfa'  => 'FEE2E2',
                    default => 'FFFFFF',
                };
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
            }
        }

        // Currency format
        $sheet->getStyle('K4:L' . $lastRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');

        return [];
    }
}
