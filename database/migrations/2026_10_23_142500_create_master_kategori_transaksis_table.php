<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_kategori_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique();
            $table->enum('tipe', ['masuk', 'keluar', 'umum'])->default('umum')->index();
            $table->timestamps();
        });

        // Seed initial standard categories
        $now = now();
        $initialData = [
            // Pemasukan (Masuk)
            ['nama_kategori' => 'Pembayaran SPP', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Tagihan Non-SPP / Wisuda', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pemasukan Lain Yayasan', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pemasukan Hibah & Kerjasama', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Donasi / Infaq', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pendaftaran PMB', 'tipe' => 'masuk', 'created_at' => $now, 'updated_at' => $now],

            // Pengeluaran (Keluar)
            ['nama_kategori' => 'Pengeluaran Operasional', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Gaji & Honorarium', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Sarana & Prasarana Kampus', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Listrik, Air & Internet', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kegiatan Kemahasiswaan', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pemeliharaan & Renovasi', 'tipe' => 'keluar', 'created_at' => $now, 'updated_at' => $now],

            // Umum
            ['nama_kategori' => 'Lain-lain', 'tipe' => 'umum', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('master_kategori_transaksis')->insert($initialData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kategori_transaksis');
    }
};
