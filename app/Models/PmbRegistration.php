<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PmbRegistration extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email' => 'encrypted',
        'whatsapp_number' => 'encrypted',
        'address' => 'encrypted',
        'full_name' => 'encrypted',
    ];
}
