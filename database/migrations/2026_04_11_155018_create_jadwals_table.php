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
        Schema::create('jadwals', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('mata_kuliah');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('program_studi_id')->constrained('program_studis')->onDelete('cascade');
            $table->string('hari'); // Senin, Selasa, etc.
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang');
            $table->integer('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
