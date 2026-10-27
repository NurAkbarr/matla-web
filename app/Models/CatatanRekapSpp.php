<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanRekapSpp extends Model
{
    protected $table = 'catatan_rekap_spps';

    protected $fillable = [
        'user_id',
        'tahun_akademik',
        'tipe_semester',
        'catatan',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
