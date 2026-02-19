<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'tenant_id', 'website_id', 'title', 'slug', 'content', 'sections',
        'template', 'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'is_published', 'sort_order'
    ];

    protected $casts = [
        'sections' => 'array',
        'is_published' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
