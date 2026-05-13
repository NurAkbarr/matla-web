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
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->integer('pertemuan_ke'); // 1, 2, 3, dst
            $table->string('judul_materi');
            $table->enum('tipe_pertemuan', ['video', 'zoom']);
            $table->string('link_url')->nullable(); // URL YouTube atau Zoom
            $table->text('deskripsi')->nullable(); // Instruksi atau silabus
            $table->text('soal_evaluasi')->nullable(); // Soal kuis untuk video
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};
