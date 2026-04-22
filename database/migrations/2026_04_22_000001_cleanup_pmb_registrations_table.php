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
        Schema::table('pmb_registrations', function (Blueprint $table) {
            // Drop obsolete columns
            $table->dropColumn([
                'skill_level',
                'skill_100_desc',
                'focus_opinion',
                'comparison_opinion',
                'target_skill',
                'last_education'
            ]);

            // Add ip_address column for security auditing (if not exists)
            if (!Schema::hasColumn('pmb_registrations', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pmb_registrations', function (Blueprint $table) {
            $table->integer('skill_level')->nullable();
            $table->text('skill_100_desc')->nullable();
            $table->text('focus_opinion')->nullable();
            $table->text('comparison_opinion')->nullable();
            $table->string('target_skill')->nullable();
            $table->string('last_education')->nullable();
            $table->dropColumn('ip_address');
        });
    }
};
