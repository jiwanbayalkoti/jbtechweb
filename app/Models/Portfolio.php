<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'tenant_id', 'title', 'slug', 'description', 'image', 'client',
        'category', 'project_url', 'project_date', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'project_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
