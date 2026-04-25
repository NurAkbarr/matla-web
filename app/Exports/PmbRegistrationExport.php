<?php

namespace App\Exports;

use App\Models\PmbRegistration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PmbRegistrationExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $status;
    protected $prodi;

    public function __construct($status = null, $prodi = null)
    {
        $this->status = $status;
        $this->prodi = $prodi;
    }

    public function query()
    {
        $query = PmbRegistration::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->prodi) {
            $query->where('study_program', $this->prodi);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'No. Registrasi',
            'Nama Lengkap',
            'Email',
            'WhatsApp',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Program Studi',
            'Alamat',
            'Sekolah Asal',
            'Tahun Lulus',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($registration): array
    {
        return [
            $registration->registration_code,
            $registration->full_name,
            $registration->email,
            $registration->whatsapp_number,
            $registration->birth_place,
            $registration->birth_date,
            $registration->gender,
            $registration->study_program,
            $registration->address,
            $registration->school_name,
            $registration->graduation_year,
            ucfirst($registration->status),
            $registration->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
