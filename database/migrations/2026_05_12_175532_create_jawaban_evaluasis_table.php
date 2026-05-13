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
        Schema::create('jawaban_evaluasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertemuan_id')->constrained('pertemuans')->onDelete('cascade');
            $table->text('jawaban'); // Jawaban esai dari mahasiswa
            $table->integer('nilai')->nullable(); // Opsional jika dosen ingin menilai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_evaluasis');
    }
};
