<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Central\ExportController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\CentralAppController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\NewsletterSubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
if (! app()->isProduction()) {
    Route::group(['prefix' => '/debug'], function () {
        Route::get('/validation', [DebugController::class, 'updateValidationRulesToNewFormat']);
    });
}

Route::get('/newsletter-confirm', [NewsletterSubscriptionController::class, 'confirm'])->name('newsletter-confirm');

Route::group(['middleware' => ['is_verified', 'need_to_install']], function () {
    Route::get('email/verify/{tenant}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::get('/tenants/pdf', [ExportController::class, 'tenantsPdf'])->name('tenants.pdf');
    Route::get('/domain-requests', CentralAppController::class)->name('domain-requests.index');

    // Central Routes
    Route::group(['as' => 'central.'], function () {
        Route::group(['middleware' => 'auth:sanctum'], function () {

            // spa view
            Route::get('/dashboard', CentralAppController::class)->name('dashboard.index');
        });
    });

    // SPA Routes
    Route::get('/{path}', CentralAppController::class)->where('path', '^(?!.*api).*$');
});
