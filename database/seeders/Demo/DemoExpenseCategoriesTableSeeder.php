<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoExpenseCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('expense_categories')->delete();

        \DB::table('expense_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Rent',
                'code' => 1,
                'slug' => 'rent',
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:21:29',
                'updated_at' => '2022-04-30 22:21:29',
            ],
            1 => [
                'id' => 2,
                'name' => 'Stationary',
                'code' => 2,
                'slug' => 'stationary',
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:22:12',
                'updated_at' => '2022-04-30 22:22:12',
            ],
        ]);
    }
}
