<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJenisTagihan extends Model
{
    use HasFactory;

    protected $table = 'master_jenis_tagihan';

    protected $fillable = [
        'nama_tagihan',
        'tipe_nominal',
        'nominal_default',
    ];

    protected $casts = [
        'nominal_default' => 'float',
    ];

    /**
     * Cek apakah jenis tagihan ini bertipe otomatis SPP
     */
    public function isOtomatisSpp(): bool
    {
        return $this->tipe_nominal === 'otomatis_spp' || strtoupper(trim($this->nama_tagihan)) === 'SPP';
    }
}
