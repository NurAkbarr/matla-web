<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('singkatan', 20);
            $table->string('jenjang', 10)->default('S1'); // S1, S2, D3, D4
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable()->default('🎓'); // emoji or icon name
            $table->string('akreditasi', 20)->nullable()->default('Baik'); // Unggul, Baik Sekali, Baik
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
