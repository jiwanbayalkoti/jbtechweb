<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Career;
use Illuminate\Http\Request;

class TenantApiController extends Controller
{
    public function pages(Request $request)
    {
        $tenant = $request->attributes->get('tenant') ?? app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $website = $tenant->websites()->first();
        if (!$website) return response()->json(['data' => []]);

        $pages = Page::where('tenant_id', $tenant->id)
            ->where('website_id', $website->id)
            ->where('is_published', true)
            ->get(['id', 'title', 'slug', 'content', 'meta_title', 'meta_description']);

        return response()->json(['data' => $pages]);
    }

    public function page(Request $request, string $slug)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $website = $tenant->websites()->first();
        if (!$website) return response()->json(['error' => 'Not found'], 404);

        $page = Page::where('tenant_id', $tenant->id)
            ->where('website_id', $website->id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$page) return response()->json(['error' => 'Not found'], 404);

        return response()->json(['data' => $page]);
    }

    public function blogs(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $posts = Blog::where('tenant_id', $tenant->id)
            ->where('is_published', true)
            ->with('user:id,name')
            ->latest('published_at')
            ->paginate($request->get('per_page', 15));

        return response()->json($posts);
    }

    public function blog(Request $request, string $slug)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $post = Blog::where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with('user:id,name')
            ->first();

        if (!$post) return response()->json(['error' => 'Not found'], 404);

        return response()->json(['data' => $post]);
    }

    public function services(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $services = Service::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $services]);
    }

    public function portfolios(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $portfolios = Portfolio::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $portfolios]);
    }

    public function testimonials(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $testimonials = Testimonial::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $testimonials]);
    }

    public function careers(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) return response()->json(['error' => 'Tenant not found'], 404);

        $careers = Career::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json(['data' => $careers]);
    }
}
