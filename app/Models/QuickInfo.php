<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickInfo extends Model
{
    protected $fillable = [
        'label',
        'link',
        'order',
        'is_active',
    ];
}
