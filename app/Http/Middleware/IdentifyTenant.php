<?php

namespace App\Http\Middleware;

use App\Services\TenantResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(
        protected TenantResolver $tenantResolver
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->tenantResolver->resolve($request->getHost());

        if ($tenant) {
            $this->tenantResolver->setTenant($tenant);
            app()->instance('tenant', $tenant);
        }

        return $next($request);
    }
}
