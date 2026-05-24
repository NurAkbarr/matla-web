<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'whatsapp_number',
        'affiliate_code',
        'commission_rate',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get display name (from user or manual input)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->name ?? 'Unknown');
    }

    /**
     * Get display whatsapp (from user or manual input)
     */
    public function getDisplayWhatsappAttribute(): string
    {
        return $this->user ? $this->user->phone : ($this->whatsapp_number ?? '-');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(PmbRegistration::class);
    }

    /**
     * Get the full affiliate link.
     */
    public function getLinkAttribute(): string
    {
        return route('pmb', ['ref' => $this->affiliate_code]);
    }
}
