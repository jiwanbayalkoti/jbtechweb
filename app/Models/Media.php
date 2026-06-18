<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $fillable = [
        'tenant_id', 'mediable_type', 'mediable_id', 'upload_group_id', 'title', 'description', 'file_name', 'file_path',
        'file_type', 'mime_type', 'file_size', 'alt_text', 'folder'
    ];

    protected $casts = ['file_size' => 'integer'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
