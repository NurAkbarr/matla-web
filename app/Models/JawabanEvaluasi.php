<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanEvaluasi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'pertemuan_id',
        'jawaban',
        'nilai',
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
