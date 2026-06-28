<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'tenant_id', 'title', 'slug', 'description', 'icon', 'image',
        'sort_order', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(ServicePlan::class)->orderBy('sort_order');
    }

    public function activePlans(): HasMany
    {
        return $this->plans()->where('is_active', true);
    }
}
