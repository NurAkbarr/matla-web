<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceWallet extends Model
{
    protected $fillable = [
        'name',
        'type',
        'balance',
        'icon',
    ];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }
}
