<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = [
        'program_studi_id',
        'kode',
        'nama',
        'sks',
        'semester',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
