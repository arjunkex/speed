<?php

namespace App\Providers;

use App\Interfaces\IDashboardService;
use App\Interfaces\ITransactionService;

use App\Services\DashboardService;
use App\Services\TransactionService;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IDashboardService::class, DashboardService::class);
        $this->app->bind(ITransactionService::class, TransactionService::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
