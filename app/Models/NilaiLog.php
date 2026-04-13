<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nilai_id',
        'user_id',
        'old_values',
        'new_values',
        'action',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
