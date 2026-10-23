<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKategoriTransaksi extends Model
{
    use HasFactory;

    protected $table = 'master_kategori_transaksis';

    protected $fillable = [
        'nama_kategori',
        'tipe',
    ];

    public function isMasuk(): bool
    {
        return $this->tipe === 'masuk';
    }

    public function isKeluar(): bool
    {
        return $this->tipe === 'keluar';
    }

    public function isUmum(): bool
    {
        return $this->tipe === 'umum';
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->tipe) {
            'masuk' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'keluar' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }
}
