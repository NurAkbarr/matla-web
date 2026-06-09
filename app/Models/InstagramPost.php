<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstagramPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'image',
        'instagram_link',
        'caption',
        'is_active',
        'order',
    ];
}
