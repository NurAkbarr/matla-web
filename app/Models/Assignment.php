<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Assignment extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'class_group_id',
        'mata_kuliah_id',
        'title',
        'description',
        'file_path',
        'link',
        'submission_type',
        'type',
        'due_date',
        'created_by'
    ];
 
    protected $casts = [
        'due_date' => 'datetime'
    ];
 
    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id');
    }
 
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
 
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
 
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }

    public function questions()
    {
        return $this->hasMany(AssignmentQuestion::class, 'assignment_id');
    }
}
