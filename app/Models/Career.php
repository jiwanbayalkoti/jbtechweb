<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Career extends Model
{
    protected $fillable = [
        'tenant_id', 'title', 'slug', 'department', 'location', 'type',
        'description', 'requirements', 'application_deadline', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'application_deadline' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
