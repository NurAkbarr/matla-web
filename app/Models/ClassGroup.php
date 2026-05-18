<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = ['prodi_id', 'angkatan', 'name'];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function students()
    {
        $prodiName = $this->prodi ? $this->prodi->nama : null;
        return User::where('role', 'mahasiswa')
            ->where('angkatan', $this->angkatan)
            ->where(function($query) use ($prodiName) {
                if ($prodiName) {
                    $query->where('education->program_studi', $prodiName);
                } else {
                    $query->whereRaw('1 = 0');
                }
            });
    }

    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }
}
