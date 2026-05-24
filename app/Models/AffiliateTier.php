<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_referrals',
        'commission_amount'
    ];
}
