<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(15);
        return view('tenant.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_title' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_active' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['rating'] = $validated['rating'] ?? 5;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = Testimonial::where('tenant_id', auth()->user()->tenant_id)->max('sort_order') + 1;
        Testimonial::create($validated);
        return response()->json(['success' => true, 'message' => 'Testimonial created', 'redirect' => route('tenant.testimonials.index')]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        if ($testimonial->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_title' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        $validated['rating'] = $validated['rating'] ?? 5;
        $validated['is_active'] = $request->boolean('is_active', true);
        $testimonial->update($validated);
        return response()->json(['success' => true, 'message' => 'Testimonial updated', 'redirect' => route('tenant.testimonials.index')]);
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->tenant_id !== auth()->user()->tenant_id) abort(403);
        $testimonial->delete();
        return response()->json(['success' => true, 'message' => 'Testimonial deleted']);
    }
}
