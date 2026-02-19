<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantDomain;
use Illuminate\Support\Facades\Cache;

class TenantResolver
{
    protected ?Tenant $tenant = null;

    public function resolve(?string $host = null): ?Tenant
    {
        if ($this->tenant) {
            return $this->tenant;
        }

        // API: check X-Tenant header or ?tenant= slug
        if (request()->is('api/*')) {
            $slug = request()->header('X-Tenant') ?? request()->query('tenant');
            if ($slug) {
                return Tenant::where('slug', $slug)->where('is_active', true)->first();
            }
        }

        $host = $host ?? request()->getHost();

        return Cache::remember("tenant.{$host}", 3600, function () use ($host) {
            $domain = TenantDomain::where('domain', $host)->where('is_verified', true)->first();
            if ($domain) {
                return $domain->tenant;
            }

            $parts = explode('.', $host);
            if (count($parts) >= 2) {
                $subdomain = $parts[0];
                if (!in_array($subdomain, ['www', 'app', 'admin', 'api', 'mail'])) {
                    return Tenant::where('slug', $subdomain)->where('is_active', true)->first();
                }
            }

            return null;
        });
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    public function clear(): void
    {
        $this->tenant = null;
    }
}
