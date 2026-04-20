<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrosurPmb extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'file',
        'is_active',
        'order',
    ];
}
