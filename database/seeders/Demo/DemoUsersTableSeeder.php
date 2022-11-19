<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoUsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert([
            0 => [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'superadmin@acculance.com',
                'email_verified_at' => '2022-04-30 22:13:36',
                'password' => '$2y$10$m.UuOomrzepx2oWcTxzhv.bfzyH4nPqEO7VoNgxK3xIpMyqzTm4uy',
                'remember_token' => null,
                'account_role' => 1,
                'is_active' => 1,
                'slug' => 'super-admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Whilemina Watts',
                'email' => 'Whilemina@mailinator.com',
                'email_verified_at' => null,
                'password' => '$2y$10$jn0Si9GEEspQCwBtK1U19e398DDfSw0Iq/UrOobFj1XY9sfn8/R9q',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'slug' => 'whilemina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => 3,
                'name' => 'Sales',
                'email' => 'sales@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$PuLuGojoP6frvUXjxWGHPegTk.ayyeIC0aBWLGS5ST.k1Chby9TPK',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'slug' => 'mari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            3 => [
                'id' => 4,
                'name' => 'Manager',
                'email' => 'manager@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$vN.8.hi/ShH7rjjdUe0Xz./5l8sZ9K/4nopOhPNBR4jjGs5tP/YOC',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'slug' => 'paki',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            4 => [
                'id' => 5,
                'name' => 'Rafsan',
                'email' => 'developer@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$PWExVPMeeRvy7c1su9ZwTONGS4ZFJCj6lSWPgJMAEfbYgY.axJtNu',
                'remember_token' => null,
                'account_role' => 1,
                'is_active' => 1,
                'slug' => 'rafsan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
