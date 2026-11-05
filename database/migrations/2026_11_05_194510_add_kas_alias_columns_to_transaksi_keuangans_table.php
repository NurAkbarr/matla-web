<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_keuangans', 'jenis_arus_kas')) {
                $table->string('jenis_arus_kas', 50)->nullable()->after('jenis_transaksi');
            }
            if (!Schema::hasColumn('transaksi_keuangans', 'kategori_id')) {
                $table->foreignId('kategori_id')->nullable()->after('kategori')->constrained('master_kategori_transaksis')->nullOnDelete();
            }
            if (!Schema::hasColumn('transaksi_keuangans', 'pemberi_dana')) {
                $table->string('pemberi_dana', 255)->nullable()->after('subjek');
            }
            if (!Schema::hasColumn('transaksi_keuangans', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('transaksi_keuangans', 'waktu_presisi')) {
                $table->string('waktu_presisi', 50)->nullable()->after('tanggal_transaksi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keuangans', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keuangans', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
            $columnsToDrop = [];
            foreach (['jenis_arus_kas', 'pemberi_dana', 'keterangan', 'waktu_presisi'] as $col) {
                if (Schema::hasColumn('transaksi_keuangans', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
