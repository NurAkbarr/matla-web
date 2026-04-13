<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah',
        'sks',
        'dosen_id',
        'program_studi_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang',
        'semester',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'jadwal_mahasiswa', 'jadwal_id', 'mahasiswa_id')->withTimestamps();
    }

    public function nilaiKomponens()
    {
        return $this->hasMany(NilaiKomponen::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function isGradesLocked()
    {
        return $this->nilais()->where('is_locked', true)->exists();
    }
}
