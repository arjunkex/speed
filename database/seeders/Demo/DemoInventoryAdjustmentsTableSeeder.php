<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoInventoryAdjustmentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('inventory_adjustments')->delete();

        \DB::table('inventory_adjustments')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Warehouse cleanup',
                'slug' => 'warehouse-cleanup',
                'code' => 1,
                'date' => '2022-04-30',
                'note' => 'This is a note',
                'status' => 1,
                'created_at' => '2022-05-01 06:04:24',
                'updated_at' => '2022-05-01 06:04:24',
                'created_by' => 1,
            ],
        ]);
    }
}
