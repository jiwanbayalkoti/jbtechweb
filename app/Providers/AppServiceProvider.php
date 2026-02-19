<?php

namespace App\Providers;

use App\Services\TenantResolver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
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
