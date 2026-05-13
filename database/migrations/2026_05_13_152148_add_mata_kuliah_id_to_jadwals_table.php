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
            // Try to add foreign key separately to handle cases where column exists but FK doesn't
            try {
                $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliahs')->onDelete('set null');
            } catch (\Exception $e) {
                // FK might already exist or fail for other reasons
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
