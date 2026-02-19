<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(15);
        return view('tenant.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = Service::where('tenant_id', auth()->user()->tenant_id)->max('sort_order') + 1;
        Service::create($validated);
        return response()->json(['success' => true, 'message' => 'Service created', 'redirect' => route('tenant.services.index')]);
    }

    public function update(Request $request, Service $service)
    {
        if ($service->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $service->update($validated);
        return response()->json(['success' => true, 'message' => 'Service updated', 'redirect' => route('tenant.services.index')]);
    }

    public function destroy(Service $service)
    {
        if ($service->tenant_id !== auth()->user()->tenant_id) abort(403);
        $service->delete();
        return response()->json(['success' => true, 'message' => 'Service deleted']);
    }
}
