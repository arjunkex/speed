<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoExpenseSubCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('expense_sub_categories')->delete();

        \DB::table('expense_sub_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Office Rent',
                'code' => 1,
                'slug' => 'office-rent',
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:21:40',
                'updated_at' => '2022-04-30 22:21:40',
                'exp_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Office Stationary',
                'code' => 2,
                'slug' => 'office-stationary',
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:23:23',
                'updated_at' => '2022-04-30 22:23:23',
                'exp_id' => 2,
            ],
        ]);
    }
}
