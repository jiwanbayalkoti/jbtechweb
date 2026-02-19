<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['tenant_id', 'group', 'key', 'value'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getValue(?int $tenantId, string $group, string $key, $default = null)
    {
        $cacheKey = "settings.{$tenantId}.{$group}.{$key}";
        return Cache::remember($cacheKey, 3600, function () use ($tenantId, $group, $key, $default) {
            $setting = static::where('tenant_id', $tenantId)
                ->where('group', $group)
                ->where('key', $key)
                ->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function setValue(?int $tenantId, string $group, string $key, $value): void
    {
        static::updateOrCreate(
            ['tenant_id' => $tenantId, 'group' => $group, 'key' => $key],
            ['value' => $value]
        );
        Cache::forget("settings.{$tenantId}.{$group}.{$key}");
    }
}
