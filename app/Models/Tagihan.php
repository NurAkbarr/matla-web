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

    public function transaksis()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }

    protected static function booted()
    {
        static::deleting(function ($tagihan) {
            $pembayaranIds = \App\Models\Pembayaran::where('tagihan_id', $tagihan->id)->pluck('id')->toArray();
            \App\Models\TransaksiKeuangan::where('tagihan_id', $tagihan->id)
                ->orWhereIn('pembayaran_id', $pembayaranIds)
                ->delete();
            \App\Models\Pembayaran::where('tagihan_id', $tagihan->id)->delete();
        });
    }
}
