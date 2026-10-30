<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id',
        'user_id',
        'jenis_pembayaran',
        'nominal',
        'bukti_tf',
        'berkas_pendukung',
        'kwitansi',
        'catatan',
        'status',
        'bulan_alokasi',
        'tahun_akademik_id',
        'catatan_alokasi',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'bulan_alokasi' => 'array',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(MasterTahunAkademik::class, 'tahun_akademik_id');
    }

    public function transaksiKeuangan()
    {
        return $this->hasOne(TransaksiKeuangan::class, 'pembayaran_id');
    }
}
