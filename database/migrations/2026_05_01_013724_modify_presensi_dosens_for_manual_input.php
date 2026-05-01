<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah foreign key benar-benar ada di database sebelum drop
        $foreignKeyExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'presensi_dosens'
            AND CONSTRAINT_NAME = 'presensi_dosens_jadwal_id_foreign'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ")[0]->count > 0;

        if ($foreignKeyExists) {
            Schema::table('presensi_dosens', function (Blueprint $table) {
                $table->dropForeign(['jadwal_id']);
            });
        }

        Schema::table('presensi_dosens', function (Blueprint $table) {
            if (Schema::hasColumn('presensi_dosens', 'jadwal_id')) {
                $table->dropColumn('jadwal_id');
            }
            if (Schema::hasColumn('presensi_dosens', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('presensi_dosens', 'status')) {
                $table->dropColumn('status');
            }

            if (!Schema::hasColumn('presensi_dosens', 'bulan')) {
                $table->string('bulan')->after('user_id');
            }
            if (!Schema::hasColumn('presensi_dosens', 'semester')) {
                $table->string('semester')->after('bulan');
            }
            if (!Schema::hasColumn('presensi_dosens', 'angkatan')) {
                $table->string('angkatan')->nullable()->after('semester');
            }
            if (!Schema::hasColumn('presensi_dosens', 'mata_kuliah')) {
                $table->string('mata_kuliah')->after('angkatan');
            }
            if (!Schema::hasColumn('presensi_dosens', 'pekan_1')) {
                $table->string('pekan_1')->nullable()->after('mata_kuliah');
            }
            if (!Schema::hasColumn('presensi_dosens', 'pekan_2')) {
                $table->string('pekan_2')->nullable()->after('pekan_1');
            }
            if (!Schema::hasColumn('presensi_dosens', 'pekan_3')) {
                $table->string('pekan_3')->nullable()->after('pekan_2');
            }
            if (!Schema::hasColumn('presensi_dosens', 'pekan_4')) {
                $table->string('pekan_4')->nullable()->after('pekan_3');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensi_dosens', function (Blueprint $table) {
            foreach (['bulan', 'semester', 'angkatan', 'mata_kuliah', 'pekan_1', 'pekan_2', 'pekan_3', 'pekan_4'] as $col) {
                if (Schema::hasColumn('presensi_dosens', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
