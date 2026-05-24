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
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            // Drop foreign key first if needed, but SQLite might have issues. Assuming MySQL here.
            $table->dropForeign(['pmb_registration_id']);
            $table->foreignId('pmb_registration_id')->nullable()->change();
            $table->foreign('pmb_registration_id')->references('id')->on('pmb_registrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            //
        });
    }
};
