<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CentralSettingSeeder::class,
            SettingImageSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            CentralPermissionSeeder::class,
            UserRoleSeeder::class,
            UserPermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // for testing purposes
        // this will not run in production environment
        if (App::environment('local') || App::environment('staging')) {
            $this->call([
                PlanSeeder::class,
                FeatureSeeder::class,
                FeaturePlanSeeder::class,
                TenantSeeder::class,
                NewsletterSubscriptionSeeder::class,
                PageSeeder::class,
                DomainRequestSeeder::class,
            ]);
        }
    }
}