<?php

namespace Database\Seeders;

use Database\Seeders\Demo\DemoDatabaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for testing purposes
        // this will not run in production environment
        if (App::environment('local')) {
            $this->call([
                DemoDatabaseSeeder::class,
            ]);
        }
        // for production
        else {
            $this->call([
                TenantRoleSeeder::class,
                TenantSettingSeeder::class,
                CurrencySeeder::class,
                UserRoleSeeder::class,
                TenantPermissionSeeder::class,
                UserPermissionSeeder::class,
                RolePermissionSeeder::class,
                TenantClientSeeder::class,
                TenantAccountSeeder::class,
                TenantVatRateSeeder::class,
            ]);
        }
    }
}
