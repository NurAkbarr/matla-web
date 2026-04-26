<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillMahasiswa extends Model
{
    protected $table = 'skill_mahasiswa';

    protected $fillable = [
        'user_id',
        'nama_skill',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
