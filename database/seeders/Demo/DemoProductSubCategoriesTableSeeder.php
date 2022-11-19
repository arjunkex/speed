<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoProductSubCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_sub_categories')->delete();

        \DB::table('product_sub_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Laptop',
                'slug' => 'laptop',
                'code' => 1,
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:46:30',
                'updated_at' => '2022-04-30 22:46:30',
                'cat_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Mobile',
                'slug' => 'mobile',
                'code' => 2,
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:46:41',
                'updated_at' => '2022-04-30 22:46:41',
                'cat_id' => 1,
            ],
        ]);
    }
}
