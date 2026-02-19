<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        if (!$request->user()->tenant_id) {
            return redirect()->route('login')->with('error', 'No tenant associated.');
        }

        $tenant = $request->user()->tenant;
        if (!$tenant || !$tenant->is_active) {
            return response()->view('errors.tenant-suspended', [], 403);
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
