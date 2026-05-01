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
        // Drop the old table completely
        Schema::dropIfExists('presensi_dosens');

        // Recreate it with the grid structure
        Schema::create('presensi_dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('bulan'); // Format YYYY-MM
            $table->string('semester');
            $table->string('angkatan');
            $table->string('mata_kuliah');
            $table->string('pekan_1')->nullable(); // Hadir, Izin, Sakit, Alfa
            $table->string('pekan_2')->nullable();
            $table->string('pekan_3')->nullable();
            $table->string('pekan_4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_dosens');
    }
};
