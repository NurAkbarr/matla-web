<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_tagihan',
        'nominal_total',
        'sisa_tagihan',
        'status',
        'jatuh_tempo',
        'keterangan',
        'jenis_tagihan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
