<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\GeneralSetting;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Dusk\DuskServiceProvider;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class AppServiceProvider extends ServiceProvider
{
    private function generalSettingAndPermission()
    {
        // if table is not empty then get setting items
        if (DB::connection()->getDatabaseName()) {
            if (Schema::hasTable('general_settings')) {
                $allSettings = GeneralSetting::get();
                // define global variables
                if (count($allSettings) > 0) {
                    config(['config.clientPrefix' => $allSettings->where('key', 'client_prefix')->first()?->value]);
                    config(['config.employeePrefix' => $allSettings->where('key', 'employee_prefix')->first()?->value]);
                    config(['config.supplierPrefix' => $allSettings->where('key', 'supplier_prefix')->first()?->value]);
                    config(['config.expCatPrefix' => $allSettings->where('key', 'exp_cat_prefix')->first()?->value]);
                    config(['config.expSubCatPrefix' => $allSettings->where('key', 'exp_sub_cat_prefix')->first()?->value]);
                    config(['config.proCatPrefix' => $allSettings->where('key', 'product_cat_prefix')->first()?->value]);
                    config(['config.proSubCatPrefix' => $allSettings->where('key', 'product_sub_cat_prefix')->first()?->value]);
                    config(['config.productPrefix' => $allSettings->where('key', 'product_prefix')->first()?->value]);
                    config(['config.purchasePrefix' => $allSettings->where('key', 'pur_prefix')->first()?->value]);
                    config(['config.purchaseReturnPrefix' => $allSettings->where('key', 'pur_return_prefix')->first()?->value]);
                    config(['config.quotationPrefix' => $allSettings->where('key', 'quotation_prefix')->first()?->value]);
                    config(['config.invoicePrefix' => $allSettings->where('key', 'invoice_prefix')->first()?->value]);
                    config(['config.invoiceReturnPrefix' => $allSettings->where('key', 'invoice_return_prefix')->first()?->value]);
                    config(['config.adjustmentPrefix' => $allSettings->where('key', 'adjustment_prefix')->first()?->value]);
                    config(['config.favicon' => $allSettings->where('key', 'favicon')->first()?->value]);
                }
            }

            if (Schema::hasTable('currencies')) {
                $currency = Currency::where('id', 1)->first();
                config(['config.currencySymbol' => $currency?->symbol]);
                config(['config.currencyPosition' => $currency?->position]);
            }
        }
        // check permission for tenant
        if (DB::connection()->getDatabaseName()) {
            if (Schema::hasTable('permissions')) {
                $permissions = Permission::all();
                if (! empty($permissions)) {
                    foreach ($permissions as $permission) {
                        Gate::define($permission->slug, function ($user) use ($permission) {
                            return $user->hasPermissionTo($permission->slug);
                        });
                    }
                }
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //define cashier model
        Cashier::useCustomerModel(Tenant::class);

        // define default string length
        // Schema::defaultStringLength(191);

        $this->generalSettingAndPermission();

        /*
         * tenant related configurations start
         */

        // after tenant bootstrapped do other checking stuff
        Event::listen(TenancyBootstrapped::class, function (TenancyBootstrapped $event) {
            $this->generalSettingAndPermission();
        });

        /*
         * tenant related configurations end
         */

        // Model::preventLazyLoading(
        //     ! app()->isProduction()
        // );

        Model::preventSilentlyDiscardingAttributes(
            ! app()->isProduction()
        );

        /*
         * Custom Macros
         */
        Builder::macro('toRawSql', function() {
            return vsprintf(str_replace(['?'], ['\'%s\''], $this->toSql()), $this->getBindings());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();
        if ($this->app->environment('local', 'testing') && class_exists(DuskServiceProvider::class)) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
