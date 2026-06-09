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
        // Add type column to assignments table
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('type')->default('upload')->after('class_group_id'); // 'upload' or 'quiz'
        });

        // Questions Table
        Schema::create('assignment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'multiple_choice', 'checkbox', 'short_answer', 'paragraph'
            $table->text('question_text');
            $table->integer('points')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        // Question Options Table (for multiple choice and checkbox)
        Schema::create('assignment_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('assignment_questions')->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // Student Answers Table
        Schema::create('assignment_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('assignment_questions')->onDelete('cascade');
            $table->text('answer_text')->nullable(); // For short answer / paragraph
            $table->integer('points_awarded')->nullable();
            $table->timestamps();
        });

        // Selected Options Table (for multiple choice and checkbox answers)
        Schema::create('assignment_quiz_selected_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('assignment_quiz_answers')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('assignment_question_options')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_quiz_selected_options');
        Schema::dropIfExists('assignment_quiz_answers');
        Schema::dropIfExists('assignment_question_options');
        Schema::dropIfExists('assignment_questions');

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
