<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSemester extends Model
{
    use HasFactory;

    protected $table = 'master_semester';

    protected $fillable = [
        'tahun_akademik_id',
        'nama_semester',
        'tipe',
        'bulan_mulai',
        'bulan_selesai',
        'is_active',
    ];

    protected $casts = [
        'bulan_mulai' => 'integer',
        'bulan_selesai' => 'integer',
        'is_active' => 'boolean',
    ];

    public function tahunAkademik()
    {
        return $this->belongsTo(MasterTahunAkademik::class, 'tahun_akademik_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Dapatkan daftar bulan lengkap (nama, singkatan, nomor bulan, tahun kalender)
     */
    public function getDaftarBulan(): array
    {
        $bulanNames = [
            1 => ['nama' => 'Januari', 'short' => 'Jan'],
            2 => ['nama' => 'Februari', 'short' => 'Feb'],
            3 => ['nama' => 'Maret', 'short' => 'Mar'],
            4 => ['nama' => 'April', 'short' => 'Apr'],
            5 => ['nama' => 'Mei', 'short' => 'Mei'],
            6 => ['nama' => 'Juni', 'short' => 'Jun'],
            7 => ['nama' => 'Juli', 'short' => 'Jul'],
            8 => ['nama' => 'Agustus', 'short' => 'Ags'],
            9 => ['nama' => 'September', 'short' => 'Sep'],
            10 => ['nama' => 'Oktober', 'short' => 'Okt'],
            11 => ['nama' => 'November', 'short' => 'Nov'],
            12 => ['nama' => 'Desember', 'short' => 'Des'],
        ];

        $tahunStr = $this->tahunAkademik->tahun ?? date('Y') . '/' . (date('Y') + 1);
        $parts = explode('/', $tahunStr);
        $startYear = isset($parts[0]) && is_numeric($parts[0]) ? (int)$parts[0] : (int)date('Y');
        $endYear = isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : ($startYear + 1);

        $daftar = [];
        $mulai = $this->bulan_mulai ?: 7;
        $selesai = $this->bulan_selesai ?: 12;

        if ($mulai <= $selesai) {
            for ($m = $mulai; $m <= $selesai; $m++) {
                // Jika semester ganjil (biasanya Juli-Desember), tahun = startYear
                // Jika semester genap (Januari-Juni), tahun = endYear
                $year = ($m >= 7) ? $startYear : $endYear;
                $daftar[] = [
                    'nama' => $bulanNames[$m]['nama'],
                    'short' => $bulanNames[$m]['short'],
                    'num' => $m,
                    'year' => $year,
                    'key' => sprintf('%04d-%02d', $year, $m),
                ];
            }
        } else {
            // Melintasi pergantian tahun kalender
            for ($m = $mulai; $m <= 12; $m++) {
                $daftar[] = [
                    'nama' => $bulanNames[$m]['nama'],
                    'short' => $bulanNames[$m]['short'],
                    'num' => $m,
                    'year' => $startYear,
                    'key' => sprintf('%04d-%02d', $startYear, $m),
                ];
            }
            for ($m = 1; $m <= $selesai; $m++) {
                $daftar[] = [
                    'nama' => $bulanNames[$m]['nama'],
                    'short' => $bulanNames[$m]['short'],
                    'num' => $m,
                    'year' => $endYear,
                    'key' => sprintf('%04d-%02d', $endYear, $m),
                ];
            }
        }

        return $daftar;
    }
}
