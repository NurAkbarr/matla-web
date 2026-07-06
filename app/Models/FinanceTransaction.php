<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_category_id',
        'finance_wallet_id',
        'amount',
        'type',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function wallet()
    {
        return $this->belongsTo(FinanceWallet::class, 'finance_wallet_id');
    }
}
