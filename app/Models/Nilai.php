<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'mahasiswa_id',
        'data_skor', // JSON: {component_id: score}
        'total_angka',
        'nilai_huruf',
        'is_locked',
        'is_published',
    ];

    protected $casts = [
        'data_skor' => 'array',
        'is_locked' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function logs()
    {
        return $this->hasMany(NilaiLog::class);
    }

    /**
     * Calculate and set total score and letter grade.
     */
    public function calculateGrade()
    {
        // Get components for this schedule
        $components = NilaiKomponen::where('jadwal_id', $this->jadwal_id)->get();
        
        $total = 0;
        $scores = $this->data_skor ?? [];

        foreach ($components as $comp) {
            $score = $scores[$comp->id] ?? 0;
            $total += ($score * $comp->bobot / 100);
        }

        $this->total_angka = round($total, 2);
        $this->nilai_huruf = $this->getLetterGrade($this->total_angka);
    }

    private function getLetterGrade($score)
    {
        if ($score >= 80) return 'A';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'C+';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }
}
