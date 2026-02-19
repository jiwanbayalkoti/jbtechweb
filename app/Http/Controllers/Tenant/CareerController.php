<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(15);
        return view('tenant.careers.index', compact('careers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|in:full_time,part_time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['type'] = $validated['type'] ?? 'full_time';
        $validated['is_active'] = $request->boolean('is_active', true);
        Career::create($validated);
        return response()->json(['success' => true, 'message' => 'Career created', 'redirect' => route('tenant.careers.index')]);
    }

    public function update(Request $request, Career $career)
    {
        if ($career->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|in:full_time,part_time,contract,internship',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['type'] = $validated['type'] ?? 'full_time';
        $validated['is_active'] = $request->boolean('is_active', true);
        $career->update($validated);
        return response()->json(['success' => true, 'message' => 'Career updated', 'redirect' => route('tenant.careers.index')]);
    }

    public function destroy(Career $career)
    {
        if ($career->tenant_id !== auth()->user()->tenant_id) abort(403);
        $career->delete();
        return response()->json(['success' => true, 'message' => 'Career deleted']);
    }
}
