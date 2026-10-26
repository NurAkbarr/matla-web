<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun')->unique(); // contoh: '2025/2026', '2026/2027'
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed data awal
        DB::table('master_tahun_akademik')->insertOrIgnore([
            [
                'tahun' => '2025/2026',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => '2026/2027',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tahun_akademik');
    }
};
