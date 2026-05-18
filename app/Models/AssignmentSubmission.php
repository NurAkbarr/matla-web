<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class AssignmentSubmission extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'assignment_id',
        'student_id',
        'submitted_file_path',
        'submitted_link',
        'notes',
        'score',
        'feedback',
        'submitted_at'
    ];
 
    protected $casts = [
        'submitted_at' => 'datetime'
    ];
 
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
 
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
