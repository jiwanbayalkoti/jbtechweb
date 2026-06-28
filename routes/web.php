<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (app()->bound('tenant')) {
        return app(TenantPublicController::class)->domainHome();
    }

    $tenantSlug = config('app.default_tenant_slug');
    if ($tenantSlug && \App\Models\Tenant::where('slug', $tenantSlug)->where('is_active', true)->exists()) {
        return app(TenantPublicController::class)->home($tenantSlug);
    }

    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Clean public website URLs for the default/domain tenant.
Route::middleware('identify.tenant')->group(function () {
    Route::get('/home', [TenantPublicController::class, 'domainHome'])->name('public.home');
    Route::get('/page/{slug}', [TenantPublicController::class, 'domainPage'])->name('public.page');
    Route::get('/blog', [TenantPublicController::class, 'domainBlogIndex'])->name('public.blog.index');
    Route::get('/blog/{slug}', [TenantPublicController::class, 'domainBlog'])->name('public.blog');
    Route::get('/service', [TenantPublicController::class, 'domainServices'])->name('public.services');
    Route::get('/service/{slug}', [TenantPublicController::class, 'domainServiceShow'])->name('public.service.show');
    Route::post('/service/{slug}/buy', [TenantPublicController::class, 'domainServicePlanBuy'])->name('public.service.buy');
    Route::redirect('/services', '/service');
    Route::get('/portfolio', [TenantPublicController::class, 'domainPortfolio'])->name('public.portfolio');
    Route::get('/portfolio/{slug}', [TenantPublicController::class, 'domainPortfolioShow'])->name('public.portfolio.show');
    Route::get('/testimonial', [TenantPublicController::class, 'domainTestimonials'])->name('public.testimonials');
    Route::get('/testimonial/{id}', [TenantPublicController::class, 'domainTestimonialShow'])->name('public.testimonial.show');
    Route::redirect('/testimonials', '/testimonial');
    Route::get('/career', [TenantPublicController::class, 'domainCareers'])->name('public.careers');
    Route::get('/career/{slug}', [TenantPublicController::class, 'domainCareerShow'])->name('public.career.show');
    Route::redirect('/careers', '/career');
    Route::get('/media', [TenantPublicController::class, 'domainMedia'])->name('public.media');
    Route::get('/media/{media}', [TenantPublicController::class, 'domainMediaShow'])->name('public.media.show');
});

// Tenant public website (path-based: /s/{tenant} for local dev without subdomain)
Route::prefix('s/{tenant}')->group(function () {
    Route::get('/', [TenantPublicController::class, 'home'])->name('tenant.public.home');
    Route::get('/page/{slug}', [TenantPublicController::class, 'page'])->name('tenant.public.page');
    Route::get('/blog', [TenantPublicController::class, 'blogIndex'])->name('tenant.public.blog.index');
    Route::get('/blog/{slug}', [TenantPublicController::class, 'blog'])->name('tenant.public.blog');
    Route::get('/services', [TenantPublicController::class, 'services'])->name('tenant.public.services');
    Route::get('/services/{slug}', [TenantPublicController::class, 'serviceShow'])->name('tenant.public.service.show');
    Route::post('/services/{slug}/buy', [TenantPublicController::class, 'servicePlanBuy'])->name('tenant.public.service.buy');
    Route::get('/portfolio', [TenantPublicController::class, 'portfolio'])->name('tenant.public.portfolio');
    Route::get('/portfolio/{slug}', [TenantPublicController::class, 'portfolioShow'])->name('tenant.public.portfolio.show');
    Route::get('/testimonials', [TenantPublicController::class, 'testimonials'])->name('tenant.public.testimonials');
    Route::get('/testimonials/{id}', [TenantPublicController::class, 'testimonialShow'])->name('tenant.public.testimonial.show');
    Route::get('/careers', [TenantPublicController::class, 'careers'])->name('tenant.public.careers');
    Route::get('/careers/{slug}', [TenantPublicController::class, 'careerShow'])->name('tenant.public.career.show');
    Route::get('/media', [TenantPublicController::class, 'media'])->name('tenant.public.media');
    Route::get('/media/{media}', [TenantPublicController::class, 'mediaShow'])->name('tenant.public.media.show');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->tenant_id) {
        return redirect()->route('tenant.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/admin.php';
require __DIR__.'/tenant.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
