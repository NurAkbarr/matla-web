<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilMahasiswa extends Model
{
    protected $table = 'profil_mahasiswa';

    protected $fillable = [
        'user_id',
        'tentang_saya',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name ?? 'M') . '&color=059669&background=ECFDF5&bold=true&size=256';
    }
}
