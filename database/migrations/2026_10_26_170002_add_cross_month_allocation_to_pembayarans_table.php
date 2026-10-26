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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->json('bulan_alokasi')->nullable()->after('status');
            $table->foreignId('tahun_akademik_id')->nullable()->after('bulan_alokasi')->constrained('master_tahun_akademik')->nullOnDelete();
            $table->text('catatan_alokasi')->nullable()->after('tahun_akademik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['tahun_akademik_id']);
            $table->dropColumn(['bulan_alokasi', 'tahun_akademik_id', 'catatan_alokasi']);
        });
    }
};
