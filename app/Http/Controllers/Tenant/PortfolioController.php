<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(15);
        return view('tenant.portfolios.index', compact('portfolios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = Portfolio::where('tenant_id', auth()->user()->tenant_id)->max('sort_order') + 1;
        $validated['image'] = $this->extractFirstImagePath($validated['description'] ?? '');
        Portfolio::create($validated);
        return response()->json(['success' => true, 'message' => 'Portfolio created', 'redirect' => route('tenant.portfolios.index')]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['image'] = $this->extractFirstImagePath($validated['description'] ?? '');
        $portfolio->update($validated);
        return response()->json(['success' => true, 'message' => 'Portfolio updated', 'redirect' => route('tenant.portfolios.index')]);
    }

    protected function extractFirstImagePath(?string $html): ?string
    {
        if (!$html) return null;
        if (!preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $m)) return null;
        $url = trim($m[1]);
        if (str_contains($url, '/storage/')) {
            return preg_replace('#^[^/]*/storage/#', '', $url);
        }
        return null;
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->tenant_id !== auth()->user()->tenant_id) abort(403);
        $portfolio->delete();
        return response()->json(['success' => true, 'message' => 'Portfolio deleted']);
    }
}
