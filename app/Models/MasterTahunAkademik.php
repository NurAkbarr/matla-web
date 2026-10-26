<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'master_tahun_akademik';

    protected $fillable = [
        'tahun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function semesters()
    {
        return $this->hasMany(MasterSemester::class, 'tahun_akademik_id');
    }

    public function activeSemester()
    {
        return $this->hasOne(MasterSemester::class, 'tahun_akademik_id')->where('is_active', true);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'tahun_akademik_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
