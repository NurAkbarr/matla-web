<?php

namespace App\Models;

/**
 * Model BukuKas
 * Alias model untuk TransaksiKeuangan (Pencatatan Kas / Buku Kas Umum)
 */
class BukuKas extends TransaksiKeuangan
{
    protected $table = 'transaksi_keuangans';
}

