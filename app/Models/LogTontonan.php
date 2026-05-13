<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTontonan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'pertemuan_id',
        'detik_ditonton',
        'is_lulus_nonton',
    ];

    protected $casts = [
        'is_lulus_nonton' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
