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
        Schema::create('pmb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique();
            
            // Section 1: Data Pribadi
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('reference')->nullable();
            $table->string('nik', 20)->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('whatsapp_number');
            $table->string('email');
            $table->text('address');
            $table->string('activity_status');

            // Section 2: Data Pendidikan
            $table->string('last_education');
            $table->string('school_name');
            $table->string('graduation_year', 4);
            $table->string('study_program');

            // Section 3: Ilmu Syar'i & Tech
            $table->integer('skill_level');
            $table->text('skill_100_desc')->nullable();
            $table->text('urgency_opinion');
            $table->text('focus_opinion');
            $table->text('comparison_opinion');
            $table->string('target_skill');
            $table->string('main_interest');
            $table->text('motivation');

            // Section 4: Administrasi
            $table->string('payment_proof');
            $table->enum('status', ['pending', 'verified', 'rejected', 'accepted'])->default('pending');
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_registrations');
    }
};
