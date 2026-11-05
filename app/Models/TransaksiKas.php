<?php

namespace App\Models;

/**
 * Model TransaksiKas
 * Alias model untuk TransaksiKeuangan (Pencatatan Kas Masuk & Kas Keluar)
 */
class TransaksiKas extends TransaksiKeuangan
{
    protected $table = 'transaksi_keuangans';
}

