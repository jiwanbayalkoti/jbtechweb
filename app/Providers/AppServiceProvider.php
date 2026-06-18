<?php

namespace App\Providers;

use App\Services\TenantResolver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
    }
}
