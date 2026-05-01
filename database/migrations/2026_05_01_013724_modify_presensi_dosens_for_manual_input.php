<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration ini bersifat aman (idempotent) — hanya berjalan jika kolom/foreign key lama masih ada.
     * Pada server baru yang fresh, migration create sudah langsung menggunakan skema baru, sehingga
     * migration ini tidak akan menemukan kolom lama dan akan dilewati dengan aman.
     */
    public function up(): void
    {
        Schema::table('presensi_dosens', function (Blueprint $table) {
            // Hanya drop foreign key jika masih ada
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_keys($sm->listTableForeignKeys('presensi_dosens'));

            if (in_array('presensi_dosens_jadwal_id_foreign', $foreignKeys)) {
                $table->dropForeign(['jadwal_id']);
            }

            // Hanya drop kolom jika masih ada
            $columns = Schema::getColumnListing('presensi_dosens');

            if (in_array('jadwal_id', $columns)) {
                $table->dropColumn('jadwal_id');
            }

            if (in_array('tanggal', $columns)) {
                $table->dropColumn('tanggal');
            }

            if (in_array('status', $columns)) {
                $table->dropColumn('status');
            }

            // Tambah kolom baru hanya jika belum ada
            if (!in_array('bulan', $columns)) {
                $table->string('bulan')->after('user_id');
            }

            if (!in_array('semester', $columns)) {
                $table->string('semester')->after('bulan');
            }

            if (!in_array('angkatan', $columns)) {
                $table->string('angkatan')->nullable()->after('semester');
            }

            if (!in_array('mata_kuliah', $columns)) {
                $table->string('mata_kuliah')->after('angkatan');
            }

            if (!in_array('pekan_1', $columns)) {
                $table->string('pekan_1')->nullable()->after('mata_kuliah');
            }

            if (!in_array('pekan_2', $columns)) {
                $table->string('pekan_2')->nullable()->after('pekan_1');
            }

            if (!in_array('pekan_3', $columns)) {
                $table->string('pekan_3')->nullable()->after('pekan_2');
            }

            if (!in_array('pekan_4', $columns)) {
                $table->string('pekan_4')->nullable()->after('pekan_3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_dosens', function (Blueprint $table) {
            $columns = Schema::getColumnListing('presensi_dosens');

            if (in_array('bulan', $columns))      $table->dropColumn('bulan');
            if (in_array('semester', $columns))   $table->dropColumn('semester');
            if (in_array('angkatan', $columns))   $table->dropColumn('angkatan');
            if (in_array('mata_kuliah', $columns)) $table->dropColumn('mata_kuliah');
            if (in_array('pekan_1', $columns))    $table->dropColumn('pekan_1');
            if (in_array('pekan_2', $columns))    $table->dropColumn('pekan_2');
            if (in_array('pekan_3', $columns))    $table->dropColumn('pekan_3');
            if (in_array('pekan_4', $columns))    $table->dropColumn('pekan_4');
        });
    }
};
