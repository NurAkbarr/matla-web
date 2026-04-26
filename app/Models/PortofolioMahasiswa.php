<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortofolioMahasiswa extends Model
{
    protected $table = 'portofolio_mahasiswa';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'link',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
