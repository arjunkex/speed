<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoProductCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_categories')->delete();

        \DB::table('product_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'code' => 1,
                'note' => null,
                'status' => 1,
                'created_at' => '2022-04-30 22:45:59',
                'updated_at' => '2022-04-30 22:45:59',
            ],
            1 => [
                'id' => 2,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'code' => 2,
                'note' => null,
                'status' => 1,
                'created_at' => '2022-04-30 22:46:12',
                'updated_at' => '2022-04-30 22:46:12',
            ],
        ]);
    }
}
