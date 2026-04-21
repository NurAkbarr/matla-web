<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pmb_registrations', function (Blueprint $table) {
            // Rename first_name to full_name
            $table->renameColumn('first_name', 'full_name');
            // Drop nik and last_name columns
            $table->dropColumn(['nik', 'last_name']);
            // Add registration_type enum
            $table->enum('registration_type', ['pai', 'idad'])->default('pai');
            // Add new fields (nullable for idad path)
            $table->enum('tech_experience', ['Belum Pernah', 'Dasar', 'Menengah', 'Mahir'])->nullable();
            $table->string('skill_to_learn')->nullable();
            $table->boolean('commitment_check')->default(false);
            $table->text('future_career')->nullable();
            $table->text('degree_importance')->nullable();
            // Make education related fields nullable for idad path
            $table->string('last_education')->nullable()->change();
            $table->year('graduation_year')->nullable()->change();
            $table->string('school_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pmb_registrations', function (Blueprint $table) {
            // Revert changes
            $table->renameColumn('full_name', 'first_name');
            $table->string('last_name')->nullable();
            $table->string('nik')->nullable();
            $table->dropColumn(['registration_type', 'tech_experience', 'skill_to_learn', 'commitment_check', 'future_career', 'degree_importance']);
            $table->string('last_education')->nullable(false)->change();
            $table->year('graduation_year')->nullable(false)->change();
            $table->string('school_name')->nullable(false)->change();
        });
    }
};
