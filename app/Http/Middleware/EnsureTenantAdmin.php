<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        if (!$tenant) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tenant not found.'], 404);
            }
            return redirect()->route('login')->with('error', 'Invalid subdomain or domain.');
        }

        if (!$tenant->is_active) {
            return response()->view('errors.tenant-suspended', [], 403);
        }

        return $next($request);
    }
}
