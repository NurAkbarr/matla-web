<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $fillable = [
        'jadwal_id',
        'pertemuan_ke',
        'judul_materi',
        'tipe_pertemuan',
        'link_url',
        'deskripsi',
        'soal_evaluasi',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function logTontonans()
    {
        return $this->hasMany(LogTontonan::class);
    }

    public function jawabanEvaluasis()
    {
        return $this->hasMany(JawabanEvaluasi::class);
    }
}
