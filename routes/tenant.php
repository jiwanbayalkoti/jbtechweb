<?php

use App\Http\Controllers\Tenant\BlogController;
use App\Http\Controllers\Tenant\CareerController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\MediaController;
use App\Http\Controllers\Tenant\PageController;
use App\Http\Controllers\Tenant\PlanRequestController;
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
    Route::post('services/{service}/plans', [ServiceController::class, 'storePlan'])->name('services.plans.store');
    Route::put('services/{service}/plans/{plan}', [ServiceController::class, 'updatePlan'])->name('services.plans.update');
    Route::delete('services/{service}/plans/{plan}', [ServiceController::class, 'destroyPlan'])->name('services.plans.destroy');
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('plan-requests', [PlanRequestController::class, 'index'])->name('plan-requests.index');
    Route::get('plan-requests/{planRequest}', [PlanRequestController::class, 'show'])->name('plan-requests.show');
    Route::post('plan-requests/{planRequest}/approve', [PlanRequestController::class, 'approve'])->name('plan-requests.approve');
    Route::post('plan-requests/{planRequest}/send-email', [PlanRequestController::class, 'sendEmail'])->name('plan-requests.send-email');
    Route::post('plan-requests/{planRequest}/mark-read', [PlanRequestController::class, 'markRead'])->name('plan-requests.mark-read');
    Route::resource('portfolios', PortfolioController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('careers', CareerController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('editor/upload-image', [MediaController::class, 'uploadEditorImage'])->name('editor.upload-image');
    Route::get('media/{medium}/preview', [MediaController::class, 'preview'])->name('media.preview');
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
});
