<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PlanRequestController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        if (!auth()->check()) {
            session()->put('url.intended', route('admin.dashboard'));
            return view('auth.login');
        }

        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return app(DashboardController::class)->index();
    })->name('dashboard');

    Route::middleware(['auth', 'super.admin'])->group(function () {
    Route::resource('tenants', TenantController::class);
    Route::resource('plans', PlanController::class)->except(['create', 'show']);
    Route::get('plan-requests', [PlanRequestController::class, 'index'])->name('plan-requests.index');
    Route::get('plan-requests/{planRequest}', [PlanRequestController::class, 'show'])->name('plan-requests.show');
    Route::post('plan-requests/{planRequest}/approve', [PlanRequestController::class, 'approve'])->name('plan-requests.approve');
    Route::post('plan-requests/{planRequest}/send-email', [PlanRequestController::class, 'sendEmail'])->name('plan-requests.send-email');
    Route::post('plan-requests/{planRequest}/mark-read', [PlanRequestController::class, 'markRead'])->name('plan-requests.mark-read');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
