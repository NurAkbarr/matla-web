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
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('konten');
            $table->string('target_role')->default('semua'); // semua, mahasiswa, dosen, admin, custom_email
            $table->text('custom_emails')->nullable();
            $table->string('status')->default('draft'); // draft, scheduled, sending, completed, failed
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->integer('total_penerima')->default(0);
            $table->integer('total_berhasil')->default(0);
            $table->integer('total_gagal')->default(0);
            $table->json('log_pengiriman')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
    }
};
