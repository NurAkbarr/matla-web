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
        Schema::table('presensi_dosens', function (Blueprint $table) {
            // Drop foreign key and column safely
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');

            // Add new text columns
            $table->string('mata_kuliah')->after('user_id');
            $table->string('angkatan')->nullable()->after('mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_dosens', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->onDelete('set null');
            $table->dropColumn(['mata_kuliah', 'angkatan']);
        });
    }
};
