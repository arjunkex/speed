<?php

use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Central\BillingHistoryController;
use App\Http\Controllers\Central\CentralGeneralController;
use App\Http\Controllers\Central\CentralSettingImageController;
use App\Http\Controllers\Central\CentralSubscriptionInvoiceController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\DomainController;
use App\Http\Controllers\Central\DomainRequestController;
use App\Http\Controllers\Central\FeatureController;
use App\Http\Controllers\Central\NewsletterController;
use App\Http\Controllers\Central\PageController;
use App\Http\Controllers\Central\PlanController;
use App\Http\Controllers\Central\SendNotificationController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\TenantDomainFindController;
use App\Http\Controllers\TenantRegisterController;

/*
 * API Routes for central part of the application.
 */

Route::post('/newsletter-send-confirmation', [NewsletterSubscriptionController::class, 'sendConfirmation'])
    ->name('newsletter-send-confirmation')
    ->middleware('throttle:10,1');
Route::get('/newsletter-unsubscribe/{email}', [NewsletterSubscriptionController::class, 'unsubscribe'])
    ->name('newsletter-unsubscribe');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

Route::post('email/resend', [VerificationController::class, 'resend']);

Route::get('general-settings', [CentralGeneralController::class, 'getGeneralSettings']);

// Tenant Register and Find Domain and Login API
Route::post('/register', [TenantRegisterController::class, 'store']);
Route::post('/find-domain', [TenantDomainFindController::class, 'findDomain'])->name('central.find-domain');
Route::post('/login', [LoginController::class, 'login'])->name('central.login');


// return dynamic pages content
Route::get('pages-by-slug/{slug}', [PageController::class, 'showBySlug']);

Route::group(['middleware' => 'auth:sanctum', 'as' => 'central.'], function () {
    // update profile
    Route::post('/update-profile', [DashboardController::class, 'updateProfile']);

    Route::get('/impersonate/{tenant}', [TenantController::class, 'impersonate'])->name('tenant.impersonate');
    Route::get('user', [UserController::class, 'current']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user/{slug}', [UserController::class, 'show']);
    Route::patch('user/{slug}', [UserController::class, 'update']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('user/search', [UserController::class, 'search']);
    Route::get('get-role', [UserController::class, 'roles']);
    Route::delete('user/{slug}', [UserController::class, 'destroy']);

    Route::post('logout', [LoginController::class, 'logout']);
    // general settings
    Route::post('update-settings', [CentralGeneralController::class, 'updateGeneralSettings']);
    Route::get('get-advanced-settings', [CentralGeneralController::class, 'getAdvancedSettings']);
    Route::post('update-advanced-settings', [CentralGeneralController::class, 'updateAdvancedSettings']);
    // Plan management routes
    Route::get('plans/search', [PlanController::class, 'search']);
    Route::apiResource('plans', PlanController::class);

    // Plan Feature management routes
    Route::get('features/search', [FeatureController::class, 'search']);
    Route::apiResource('features', FeatureController::class);

    // Page management routes
    Route::get('pages/search', [PageController::class, 'search']);
    Route::apiResource('pages', PageController::class);

    // Tenant management routes
    Route::get('tenants/search', [TenantController::class, 'search']);
    Route::post('tenants/{tenant}/ban', [TenantController::class, 'ban']);
    Route::apiResource('tenants', TenantController::class);
    Route::post('send-notification/{tenant}', SendNotificationController::class);

    // newsletter
    Route::post('/newsletters', [NewsletterController::class, 'send']);
    Route::get('/newsletters/search', [NewsletterController::class, 'search']);
    Route::apiResource('/newsletters', NewsletterController::class)->only(['index', 'destroy']);

    // frontend panel settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/hero-settings', [CentralGeneralController::class, 'getHeroSettings']);
        Route::patch('/hero-settings', [CentralGeneralController::class, 'updateHeroSettings']);

        Route::get('/about-us-settings', [CentralGeneralController::class, 'getAboutUsSettings']);
        Route::patch('/about-us-settings', [CentralGeneralController::class, 'updateAboutUsSettings']);

        Route::get('/why-us-settings', [CentralGeneralController::class, 'getWhyUsSettings']);
        Route::patch('/why-us-settings', [CentralGeneralController::class, 'updateWhyUsSettings']);

        Route::get('/business-start-settings', [CentralGeneralController::class, 'getBusinessStartSettings']);
        Route::patch('/business-start-settings', [CentralGeneralController::class, 'updateBusinessStartSettings']);

        Route::get('/features-settings', [CentralGeneralController::class, 'getFeaturesSettings']);
        Route::patch('/features-settings', [CentralGeneralController::class, 'updateFeaturesSettings']);

        Route::get('/all-features-settings', [
            CentralGeneralController::class,
            'getAllFeaturesSettings',
        ]);

        Route::get('/get-started-settings', [
            CentralGeneralController::class,
            'getGetStartedSettings',
        ]);

        Route::patch('/all-features-settings', [
            CentralGeneralController::class,
            'updateAllFeaturesSettings',
        ]);

        Route::patch('/get-started-settings', [
            CentralGeneralController::class,
            'updateGetStartedSettings',
        ]);

        Route::get('/software-overview-settings', [
            CentralGeneralController::class,
            'getSoftwareOverviewSettings',
        ]);
        Route::patch('/software-overview-settings', [
            CentralGeneralController::class,
            'updateSoftwareOverviewSettings',
        ]);

        Route::get('/pricing-plan-settings', [
            CentralGeneralController::class,
            'getPricingPlanSettings',
        ]);
        Route::patch('/pricing-plan-settings', [
            CentralGeneralController::class,
            'updatePricingPlanSettings',
        ]);

        Route::get('/newsletter-settings', [
            CentralGeneralController::class,
            'getNewsletterSettings',
        ]);
        Route::patch('/newsletter-settings', [
            CentralGeneralController::class,
            'updateNewsletterSettings',
        ]);

        Route::get('/testimonial-settings', [
            CentralGeneralController::class,
            'getTestimonialSettings',
        ]);
        Route::patch('/testimonial-settings', [
            CentralGeneralController::class,
            'updateTestimonialSettings',
        ]);

        Route::get('/custom-html-settings', [
            CentralGeneralController::class,
            'getCustomHtmlSettings',
        ]);
        Route::patch('/custom-html-settings', [
            CentralGeneralController::class,
            'updateCustomHtmlSettings',
        ]);
    });

    // setting images
    Route::get('/setting-images/search', [CentralSettingImageController::class, 'search']);
    Route::apiResource('/setting-images', CentralSettingImageController::class);

    // dashboard
    Route::get('/dashboard-summary/{summaryType}', [DashboardController::class, 'index']);
    Route::get('/dashboard/top-plans', [DashboardController::class, 'topPlans']);

    // client means tenants here
    Route::get('/dashboard/top-clients', [DashboardController::class, 'topClients']);

    // Permission routes
    Route::get('/permissions/search', [PermissionController::class, 'search']);
    Route::get('/all-permissions', [PermissionController::class, 'allPermissions']);
    Route::apiResource('permissions', PermissionController::class);

    // Role routes
    Route::get('/roles/search', [RoleController::class, 'search']);
    Route::get('/all-roles', [RoleController::class, 'allRoles']);
    Route::apiResource('roles', RoleController::class);

    // Billing History Routes
    Route::apiResource('/billing/history', BillingHistoryController::class)->only(['index']);

    // invoices routes
    Route::post('subscription-invoices', [CentralSubscriptionInvoiceController::class, 'store']);

    // domain management routes
    // Route::get('domains/search', [DomainController::class, 'search']);
    Route::apiResource('domains', DomainController::class)->only(['index', 'destroy']);
    Route::apiResource('domain-requests', DomainRequestController::class)->only(['index', 'update', 'destroy']);

    // database backup
    Route::post('/backup', [DashboardController::class, 'databaseBackup']);
});
