<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::where('tenant_id', auth()->user()->tenant_id)->with('website')->latest()->paginate(15);
        return view('tenant.pages.index', compact('pages'));
    }

    public function create()
    {
        $websites = auth()->user()->tenant->websites;
        return view('tenant.pages.form', ['page' => null, 'websites' => $websites]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        Page::create($validated);
        return redirect()->route('tenant.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        if ($page->tenant_id !== auth()->user()->tenant_id) abort(403);
        $websites = auth()->user()->tenant->websites;
        return view('tenant.pages.form', ['page' => $page, 'websites' => $websites]);
    }

    public function update(Request $request, Page $page)
    {
        if ($page->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $page->update($validated);
        return redirect()->route('tenant.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        if ($page->tenant_id !== auth()->user()->tenant_id) abort(403);
        $page->delete();
        return redirect()->route('tenant.pages.index')->with('success', 'Page deleted.');
    }
}
