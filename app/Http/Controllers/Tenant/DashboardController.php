<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $website = $tenant->websites()->first();
        return view('tenant.dashboard', compact('tenant', 'website'));
    }
}
