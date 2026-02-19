<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'logo', 'favicon',
        'primary_color', 'secondary_color', 'is_active', 'trial_ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'trial_ends_at' => 'date',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function primaryDomain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TenantDomain::class)->where('is_primary', true);
    }

    public function getSubdomainAttribute(): string
    {
        return $this->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST);
    }
}
