<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'type',
        'question_text',
        'points',
        'is_required',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function options()
    {
        return $this->hasMany(AssignmentQuestionOption::class, 'question_id');
    }
}
