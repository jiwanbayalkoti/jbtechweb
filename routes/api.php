<?php

use App\Http\Controllers\Api\TenantApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public tenant API (requires X-Tenant header or subdomain)
Route::middleware(['identify.tenant'])->prefix('v1/tenant')->group(function () {
    Route::get('pages', [TenantApiController::class, 'pages']);
    Route::get('pages/{slug}', [TenantApiController::class, 'page']);
    Route::get('blogs', [TenantApiController::class, 'blogs']);
    Route::get('blogs/{slug}', [TenantApiController::class, 'blog']);
    Route::get('services', [TenantApiController::class, 'services']);
    Route::get('portfolios', [TenantApiController::class, 'portfolios']);
    Route::get('testimonials', [TenantApiController::class, 'testimonials']);
    Route::get('careers', [TenantApiController::class, 'careers']);
});
