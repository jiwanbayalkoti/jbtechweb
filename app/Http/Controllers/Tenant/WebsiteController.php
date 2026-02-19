<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = auth()->user()->tenant->websites()->paginate(10);
        return view('tenant.websites.index', compact('websites'));
    }

    public function edit(Website $website)
    {
        if ($website->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        return view('tenant.websites.edit', compact('website'));
    }

    public function update(Request $request, Website $website)
    {
        if ($website->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($website->banner_image) {
                Storage::disk('public')->delete($website->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('tenant/' . $website->tenant_id . '/banner', 'public');
        } else {
            unset($validated['banner_image']);
        }

        $website->update($validated);
        return redirect()->route('tenant.websites.index')->with('success', 'Website updated.');
    }
}
