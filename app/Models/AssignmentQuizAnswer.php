<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentQuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'question_id',
        'answer_text',
        'points_awarded',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function question()
    {
        return $this->belongsTo(AssignmentQuestion::class, 'question_id');
    }

    public function selectedOptions()
    {
        return $this->belongsToMany(
            AssignmentQuestionOption::class, 
            'assignment_quiz_selected_options', 
            'answer_id', 
            'option_id'
        );
    }
}
