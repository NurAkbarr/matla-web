<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tagihans MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'belum lunas'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tagihans MODIFY COLUMN status ENUM('belum lunas', 'lunas') NOT NULL DEFAULT 'belum lunas'");
    }
};
