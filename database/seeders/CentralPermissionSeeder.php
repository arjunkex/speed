<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CentralPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('permissions')->count() == 0) {
            DB::table('permissions')->insert([
                // setup permissions
                [
                    'name' => 'General settings',
                    'guard_name' => 'Setup',
                    'slug' => 'general-settings',
                ],
                [
                    'name' => 'Payment Settings',
                    'guard_name' => 'Setup',
                    'slug' => 'payment-settings',
                ],
                [
                    'name' => 'Role & Permissions',
                    'guard_name' => 'Role & Permissions management',
                    'slug' => 'user-role',
                ],
                [
                    'name' => 'User Management',
                    'guard_name' => 'Setup',
                    'slug' => 'user-management',
                ],
                // dashboard permissions
                [
                    'name' => 'Account Summery',
                    'guard_name' => 'Dashboard View',
                    'slug' => 'account-summery',
                ],
                [
                    'name' => 'Top Plans',
                    'guard_name' => 'Dashboard View',
                    'slug' => 'top-plans',
                ],
                [
                    'name' => 'Top Clients',
                    'guard_name' => 'Dashboard View',
                    'slug' => 'top-clients',
                ],

                // other permission
                [
                    'name' => 'Billing History',
                    'guard_name' => 'Extra Management',
                    'slug' => 'billing-history',
                ],
                [
                    'name' => 'Database Backup',
                    'guard_name' => 'Extra Management',
                    'slug' => 'database-backup',
                ],
                [
                    'name' => 'Update Profile',
                    'guard_name' => 'Extra Management',
                    'slug' => 'update-profile',
                ],

                // new permissions
                // for super admin
                [
                    'name' => 'Plans Management',
                    'guard_name' => 'Plans',
                    'slug' => 'plans-management',
                ],
                [
                    'name' => 'Pricing Features',
                    'guard_name' => 'Plans',
                    'slug' => 'features-management',
                ],
                [
                    'name' => 'Tenants Management',
                    'guard_name' => 'Tenants',
                    'slug' => 'tenants-management',
                ],
                [
                    'name' => 'Landing Page Management',
                    'guard_name' => 'Landing Page',
                    'slug' => 'landing-page-management',
                ],
                [
                    'name' => 'Subscriber Management',
                    'guard_name' => 'Subscriber',
                    'slug' => 'newsletters-management',
                ],
                [
                    'name' => 'Pages Management',
                    'guard_name' => 'Pages',
                    'slug' => 'pages-management',
                ],
                [
                    'name' => 'Advanced Settings',
                    'guard_name' => 'Advanced Settings',
                    'slug' => 'advanced-settings',
                ],
                [
                    'name' => 'Promotion',
                    'guard_name' => 'Promotion',
                    'slug' => 'promotion',
                ],
                [
                    'name' => 'Domain Management',
                    'guard_name' => 'Domain Management',
                    'slug' => 'domain-management',
                ]
            ]);
        }
    }
}
