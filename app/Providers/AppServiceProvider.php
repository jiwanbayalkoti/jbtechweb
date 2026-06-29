<?php

namespace App\Providers;

use App\Models\ContactSubmission;
use App\Services\TenantResolver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (class_exists(\Laravel\Fortify\Fortify::class)) {
            \Laravel\Fortify\Fortify::$registersRoutes = false;
        }

        $this->app->singleton(TenantResolver::class, function () {
            return new TenantResolver();
        });
    }

    public function boot(): void
    {
        Paginator::useBootstrapFour();
        Paginator::defaultView('vendor.pagination.bootstrap-4');

        View::composer('admin.partials.sidebar', function ($view) {
            $count = ContactSubmission::where('subject', 'like', 'Plan inquiry:%')
                ->where('is_read', false)
                ->count();

            $view->with('planRequestUnreadCount', $count);
        });

        View::composer('tenant.partials.sidebar', function ($view) {
            $tenantId = auth()->user()?->tenant_id;
            $count = $tenantId
                ? ContactSubmission::where('tenant_id', $tenantId)
                    ->where('subject', 'like', 'Plan inquiry:%')
                    ->where('is_read', false)
                    ->count()
                : 0;

            $view->with('planRequestUnreadCount', $count);
        });
    }
}
