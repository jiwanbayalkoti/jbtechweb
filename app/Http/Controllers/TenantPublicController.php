<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Page;
use Illuminate\Http\Request;

class TenantPublicController extends Controller
{
    public function home(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404, 'Tenant not found');

        $website = $tenant->websites()->first();
        $homePage = $website ? Page::where('website_id', $website->id)->where('slug', 'home')->where('is_published', true)->first() : null;
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();

        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->take(3)->get();
        $testimonials = \App\Models\Testimonial::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->take(3)->get();
        $portfolios = \App\Models\Portfolio::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->take(3)->get();
        $blogs = \App\Models\Blog::where('tenant_id', $tenant->id)->where('is_published', true)->latest('published_at')->take(3)->get();
        $careers = \App\Models\Career::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('title')->take(3)->get();

        return view('tenant-public.home', [
            'tenant' => $tenant,
            'website' => $website,
            'page' => $homePage,
            'headerMenu' => $headerMenu,
            'publishedPages' => $publishedPages,
            'services' => $services,
            'testimonials' => $testimonials,
            'portfolios' => $portfolios,
            'blogs' => $blogs,
            'careers' => $careers,
        ]);
    }

    public function page(string $tenant, string $slug)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);

        $website = $tenant->websites()->first();
        if (!$website) abort(404);

        $page = Page::where('tenant_id', $tenant->id)
            ->where('website_id', $website->id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();

        return view('tenant-public.page', compact('tenant', 'website', 'page', 'headerMenu', 'publishedPages', 'services'));
    }

    public function blogIndex(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);

        $website = $tenant->websites()->first();
        $blogs = \App\Models\Blog::where('tenant_id', $tenant->id)
            ->where('is_published', true)
            ->with('user')
            ->latest('published_at')
            ->paginate(12);
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? \App\Models\Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();

        return view('tenant-public.blogs', compact('tenant', 'website', 'blogs', 'headerMenu', 'publishedPages', 'services'));
    }

    public function blog(string $tenant, string $slug)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);

        $website = $tenant->websites()->first();
        $post = \App\Models\Blog::where('tenant_id', $tenant->id)->where('slug', $slug)->where('is_published', true)->firstOrFail();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? \App\Models\Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();

        return view('tenant-public.blog', compact('tenant', 'website', 'post', 'headerMenu', 'publishedPages', 'services'));
    }

    public function services(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);

        $website = $tenant->websites()->first();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();

        return view('tenant-public.services', compact('tenant', 'website', 'services', 'headerMenu', 'publishedPages'));
    }

    public function serviceShow(string $tenant, string $slug)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $service = \App\Models\Service::where('tenant_id', $tenant->id)->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.service-show', compact('tenant', 'website', 'service', 'headerMenu', 'publishedPages', 'services'));
    }

    public function portfolio(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $portfolios = \App\Models\Portfolio::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.portfolio', compact('tenant', 'website', 'portfolios', 'headerMenu', 'publishedPages', 'services'));
    }

    public function portfolioShow(string $tenant, string $slug)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $item = \App\Models\Portfolio::where('tenant_id', $tenant->id)->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.portfolio-show', compact('tenant', 'website', 'item', 'headerMenu', 'publishedPages', 'services'));
    }

    public function testimonials(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $testimonials = \App\Models\Testimonial::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.testimonials', compact('tenant', 'website', 'testimonials', 'headerMenu', 'publishedPages', 'services'));
    }

    public function testimonialShow(string $tenant, int $id)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $testimonial = \App\Models\Testimonial::where('tenant_id', $tenant->id)->where('id', $id)->where('is_active', true)->firstOrFail();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.testimonial-show', compact('tenant', 'website', 'testimonial', 'headerMenu', 'publishedPages', 'services'));
    }

    public function careers(string $tenant)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $careers = \App\Models\Career::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('title')->get();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.careers', compact('tenant', 'website', 'careers', 'headerMenu', 'publishedPages', 'services'));
    }

    public function careerShow(string $tenant, string $slug)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $job = \App\Models\Career::where('tenant_id', $tenant->id)->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.career-show', compact('tenant', 'website', 'job', 'headerMenu', 'publishedPages', 'services'));
    }

    public function media(string $tenant, \Illuminate\Http\Request $request)
    {
        $tenant = $this->resolveTenant($tenant);
        if (!$tenant) abort(404);
        $website = $tenant->websites()->first();
        $media = \App\Models\Media::where('tenant_id', $tenant->id)->latest()->paginate(24);

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('tenant-public.partials.media-items', compact('media'))->render();
            return response()->json([
                'html' => $html,
                'has_more' => $media->hasMorePages(),
                'next_page' => $media->currentPage() + 1,
            ]);
        }

        $headerMenu = \App\Models\Menu::where('tenant_id', $tenant->id)->where('location', 'header')->with('items')->first();
        $publishedPages = $website ? Page::where('website_id', $website->id)->where('is_published', true)->orderBy('sort_order')->get() : collect();
        $services = \App\Models\Service::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('sort_order')->get();
        return view('tenant-public.media', compact('tenant', 'website', 'media', 'headerMenu', 'publishedPages', 'services'));
    }

    protected function resolveTenant(?string $slug): ?Tenant
    {
        if ($slug) {
            return Tenant::where('slug', $slug)->where('is_active', true)->first();
        }
        return app('tenant');
    }
}
