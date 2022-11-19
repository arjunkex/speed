<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feature::insert([
            [
                'name' => 'Role Management',
            ],
            [
                'name' => 'VAT Rates Management',
            ],
            [
                'name' => 'Database Backup',
            ],
            [
                'name' => 'On Demand Support',
            ],
        ]);
    }
}