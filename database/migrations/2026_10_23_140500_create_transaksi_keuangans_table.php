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
        Schema::create('transaksi_keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->enum('jenis_transaksi', ['masuk', 'keluar'])->default('masuk')->index();
            $table->string('kategori', 100)->index();
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_transaksi')->index();
            $table->string('metode_pembayaran', 100)->default('Transfer Bank');
            $table->string('subjek', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayarans')->nullOnDelete();
            $table->foreignId('tagihan_id')->nullable()->constrained('tagihans')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('admin_nama', 150)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('bukti', 255)->nullable();
            $table->string('no_referensi', 100)->nullable();
            $table->enum('status', ['verified', 'pending', 'rejected'])->default('verified')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangans');
    }
};
