<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('tenant_id', auth()->user()->tenant_id)
            ->with('user')
            ->latest()
            ->paginate(15);
        return view('tenant.blogs.index', compact('blogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }
        $validated['featured_image'] = $this->extractFirstImageUrl($validated['content'] ?? '', true);
        Blog::create($validated);
        return response()->json(['success' => true, 'message' => 'Blog created', 'redirect' => route('tenant.blogs.index')]);
    }

    public function update(Request $request, Blog $blog)
    {
        if ($blog->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        if ($validated['is_published'] && !$blog->published_at) {
            $validated['published_at'] = now();
        }
        $validated['featured_image'] = $this->extractFirstImageUrl($validated['content'] ?? '', true);
        $blog->update($validated);
        return response()->json(['success' => true, 'message' => 'Blog updated', 'redirect' => route('tenant.blogs.index')]);
    }

    protected function extractFirstImageUrl(?string $html, bool $asStoragePath = false): ?string
    {
        if (!$html) return null;
        if (!preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $m)) return null;
        $url = trim($m[1]);
        if ($asStoragePath && str_contains($url, '/storage/')) {
            return preg_replace('#^[^/]*/storage/#', '', $url);
        }
        return (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : null;
    }

    public function destroy(Blog $blog)
    {
        if ($blog->tenant_id !== auth()->user()->tenant_id) abort(403);
        $blog->delete();
        return response()->json(['success' => true, 'message' => 'Blog deleted']);
    }
}
