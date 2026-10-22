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
        Schema::create('master_jenis_tagihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tagihan')->unique();
            $table->enum('tipe_nominal', ['otomatis_spp', 'manual'])->default('manual');
            $table->decimal('nominal_default', 15, 2)->nullable();
            $table->timestamps();
        });

        // Seed data awal
        DB::table('master_jenis_tagihan')->insertOrIgnore([
            [
                'nama_tagihan' => 'SPP',
                'tipe_nominal' => 'otomatis_spp',
                'nominal_default' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tagihan' => 'UTS',
                'tipe_nominal' => 'manual',
                'nominal_default' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tagihan' => 'UAS',
                'tipe_nominal' => 'manual',
                'nominal_default' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tagihan' => 'Wisuda',
                'tipe_nominal' => 'manual',
                'nominal_default' => null,
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
        Schema::dropIfExists('master_jenis_tagihan');
    }
};
