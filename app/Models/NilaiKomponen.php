<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKomponen extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'nama_komponen',
        'bobot',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
