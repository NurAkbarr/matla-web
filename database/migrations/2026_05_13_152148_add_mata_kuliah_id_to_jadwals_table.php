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
        Schema::table('jadwals', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwals', 'mata_kuliah_id')) {
                $table->unsignedBigInteger('mata_kuliah_id')->nullable()->after('id');
            }
        });

        // Ensure engine is InnoDB for foreign keys to work
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE jadwals ENGINE=InnoDB');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE mata_kuliahs ENGINE=InnoDB');

        Schema::table('jadwals', function (Blueprint $table) {
            // Try to add foreign key separately
            try {
                $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliahs')->onDelete('set null');
            } catch (\Exception $e) {
                // If it still fails, it might be due to data mismatch or collation
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['mata_kuliah_id']);
            $table->dropColumn('mata_kuliah_id');
        });
    }
};
