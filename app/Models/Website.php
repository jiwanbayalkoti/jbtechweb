<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    protected $fillable = [
        'tenant_id', 'name', 'tagline', 'description', 'logo', 'favicon', 'banner_image',
        'primary_color', 'secondary_color', 'font_family', 'dark_mode', 'default_language',
        'social_links', 'contact_info', 'footer_content'
    ];

    protected $casts = [
        'social_links' => 'array',
        'contact_info' => 'array',
        'footer_content' => 'array',
        'dark_mode' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
