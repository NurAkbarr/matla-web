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
        Schema::create('log_tontonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertemuan_id')->constrained('pertemuans')->onDelete('cascade');
            $table->integer('detik_ditonton')->default(0); // durasi dalam detik
            $table->boolean('is_lulus_nonton')->default(false); // true jika > 50%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tontonans');
    }
};
