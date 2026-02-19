<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'tenants' => Tenant::count(),
            'active_subscriptions' => Subscription::whereIn('status', ['active', 'trialing'])->count(),
            'revenue_this_month' => Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('total_amount'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];

        $recentTenants = Tenant::latest()->take(5)->get();
        $recentInvoices = Invoice::with('tenant')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentTenants', 'recentInvoices'));
    }
}
