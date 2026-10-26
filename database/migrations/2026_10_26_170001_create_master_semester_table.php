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
        Schema::create('master_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('master_tahun_akademik')->onDelete('cascade');
            $table->string('nama_semester'); // e.g. 'Ganjil (Jul-Des)' atau 'Genap (Jan-Jun)'
            $table->enum('tipe', ['ganjil', 'genap'])->default('ganjil');
            $table->unsignedTinyInteger('bulan_mulai')->default(7); // 1-12
            $table->unsignedTinyInteger('bulan_selesai')->default(12); // 1-12
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed semester awal berdasarkan master_tahun_akademik
        $ta2025 = DB::table('master_tahun_akademik')->where('tahun', '2025/2026')->first();
        $ta2026 = DB::table('master_tahun_akademik')->where('tahun', '2026/2027')->first();

        if ($ta2025) {
            DB::table('master_semester')->insert([
                [
                    'tahun_akademik_id' => $ta2025->id,
                    'nama_semester' => 'Ganjil (Jul-Des)',
                    'tipe' => 'ganjil',
                    'bulan_mulai' => 7,
                    'bulan_selesai' => 12,
                    'is_active' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tahun_akademik_id' => $ta2025->id,
                    'nama_semester' => 'Genap (Jan-Jun)',
                    'tipe' => 'genap',
                    'bulan_mulai' => 1,
                    'bulan_selesai' => 6,
                    'is_active' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($ta2026) {
            DB::table('master_semester')->insert([
                [
                    'tahun_akademik_id' => $ta2026->id,
                    'nama_semester' => 'Ganjil (Jul-Des)',
                    'tipe' => 'ganjil',
                    'bulan_mulai' => 7,
                    'bulan_selesai' => 12,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tahun_akademik_id' => $ta2026->id,
                    'nama_semester' => 'Genap (Jan-Jun)',
                    'tipe' => 'genap',
                    'bulan_mulai' => 1,
                    'bulan_selesai' => 6,
                    'is_active' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_semester');
    }
};
