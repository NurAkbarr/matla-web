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
        // Try dropping foreign key first if it exists
        try {
            Schema::table('affiliate_commissions', function (Blueprint $table) {
                $table->dropForeign(['pmb_registration_id']);
            });
        } catch (\Exception $e) {
            // Ignore error if foreign key does not exist (Error 1091)
        }

        Schema::table('affiliate_commissions', function (Blueprint $table) {
            $table->unsignedBigInteger('pmb_registration_id')->nullable()->change();
            
            // Re-add foreign key if we want to ensure it exists
            try {
                $table->foreign('pmb_registration_id')->references('id')->on('pmb_registrations')->onDelete('cascade');
            } catch (\Exception $e) {
                // Ignore if it already exists
            }
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
