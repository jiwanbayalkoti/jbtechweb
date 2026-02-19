<?php

use App\Http\Controllers\Tenant\BlogController;
use App\Http\Controllers\Tenant\CareerController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\MediaController;
use App\Http\Controllers\Tenant\PageController;
use App\Http\Controllers\Tenant\PortfolioController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Http\Controllers\Tenant\TestimonialController;
use App\Http\Controllers\Tenant\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'tenant.admin'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/', [TenantDashboardController::class, 'index'])->name('dashboard');
    Route::resource('websites', WebsiteController::class)->only(['index', 'edit', 'update']);
    Route::resource('pages', PageController::class);
    Route::resource('blogs', BlogController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('portfolios', PortfolioController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('careers', CareerController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('editor/upload-image', [MediaController::class, 'uploadEditorImage'])->name('editor.upload-image');
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
});
