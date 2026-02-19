<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('subscription.plan')->latest()->paginate(15);
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.index', compact('tenants', 'plans'));
    }

    public function create()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.form', ['tenant' => null, 'plans' => $plans]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:8|confirmed',
        ]);

        $slug = Str::slug($validated['name']);
        $existing = Tenant::where('slug', $slug)->exists();
        if ($existing) {
            $slug .= '-' . Str::random(4);
        }

        $tenant = Tenant::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        TenantDomain::create([
            'tenant_id' => $tenant->id,
            'domain' => $slug . '.' . parse_url(config('app.url'), PHP_URL_HOST),
            'is_primary' => true,
            'is_verified' => true,
        ]);

        $plan = Plan::find($validated['plan_id']);
        $trialEnds = $plan->trial_days ? now()->addDays($plan->trial_days) : null;

        $subscription = $tenant->subscriptions()->create([
            'plan_id' => $validated['plan_id'],
            'status' => $plan->trial_days ? 'trialing' : 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
            'ends_at' => $plan->trial_days ? $trialEnds : now()->addMonth(),
        ]);

        $amount = (float) $plan->monthly_price;
        $seq = Invoice::whereYear('created_at', date('Y'))->count() + 1;
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        Invoice::create([
            'tenant_id' => $tenant->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'status' => 'pending',
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => bcrypt($validated['admin_password']),
        ]);

        $tenant->websites()->create(['name' => $tenant->name . ' Website']);

        return response()->json(['success' => true, 'message' => 'Tenant created successfully', 'redirect' => route('admin.tenants.index')]);
    }

    public function show(Tenant $tenant)
    {
        return response()->json($tenant->load('users'));
    }

    public function edit(Tenant $tenant)
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.form', ['tenant' => $tenant, 'plans' => $plans]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $tenant->update(array_merge($validated, ['is_active' => $request->boolean('is_active', true)]));

        return response()->json(['success' => true, 'message' => 'Tenant updated successfully', 'redirect' => route('admin.tenants.index')]);
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['success' => true, 'message' => 'Tenant deleted successfully']);
    }
}
