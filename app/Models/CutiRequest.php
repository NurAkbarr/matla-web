<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CutiRequest extends Model
{
    protected $fillable = [
        'user_id',
        'semester_diajukan',
        'alasan',
        'pernyataan_persetujuan',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'pernyataan_persetujuan' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
