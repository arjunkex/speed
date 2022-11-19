<?php

namespace Database\Seeders;

use App\Models\FeaturePlan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FeaturePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeaturePlan::insert([
            [
                'feature_id' => 1,
                'plan_id' => 1
            ],
            [
                'feature_id' => 2,
                'plan_id' => 1
            ],
            [
                'feature_id' => 1,
                'plan_id' => 2
            ],
            [
                'feature_id' => 2,
                'plan_id' => 2
            ],
            [
                'feature_id' => 3,
                'plan_id' => 2
            ],
            [
                'feature_id' => 1,
                'plan_id' => 3
            ],
            [
                'feature_id' => 2,
                'plan_id' => 3
            ],
            [
                'feature_id' => 3,
                'plan_id' => 3
            ],
            [
                'feature_id' => 4,
                'plan_id' => 3
            ],
        ]);
    }
}