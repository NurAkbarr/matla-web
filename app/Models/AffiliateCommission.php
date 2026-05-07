<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'affiliate_id',
        'pmb_registration_id',
        'amount',
        'status',
        'paid_at',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PmbRegistration::class, 'pmb_registration_id');
    }
}
