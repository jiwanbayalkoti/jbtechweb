<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'monthly_price', 'yearly_price',
        'trial_days', 'max_pages', 'max_media', 'max_users', 'features',
        'is_active', 'sort_order'
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getPriceAttribute(bool $yearly = false): float
    {
        return $yearly ? (float) $this->yearly_price : (float) $this->monthly_price;
    }
}
