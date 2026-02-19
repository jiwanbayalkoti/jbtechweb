<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'max_pages' => 'required|integer|min:1',
            'max_media' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        Plan::create($validated);

        return response()->json(['success' => true, 'message' => 'Plan created successfully', 'redirect' => route('admin.plans.index')]);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'max_pages' => 'required|integer|min:1',
            'max_media' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $plan->update(array_merge($validated, ['is_active' => $request->boolean('is_active')]));

        return response()->json(['success' => true, 'message' => 'Plan updated successfully']);
    }

    public function edit(Plan $plan)
    {
        return response()->json($plan);
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete plan with active subscriptions'], 422);
        }
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
    }
}
