<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoProductsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->delete();

        \DB::table('products')->insert([
            0 => [
                'id' => 1,
                'name' => 'Pentium Silver N5030 15.6"',
                'slug' => 'pentium-silver-n5030-15-6-hd-laptop',
                'code' => '000001',
                'model' => 'HP 15s-du1117TU',
                'barcode_symbology' => 'CODE128',
                'tax_type' => 'Exclusive',
                'purchase_price' => 877.85,
                'regular_price' => 1000.0,
                'discount' => 5.0,
                'inventory_count' => 22.0,
                'alert_qty' => 10,
                'note' => '',
                'status' => 1,
                'image_path' => 'images/products/product-01.jpeg',
                'created_at' => '2022-04-30 22:47:47',
                'updated_at' => '2022-05-01 05:10:01',
                'sub_cat_id' => 1,
                'brand_id' => 1,
                'unit_id' => 1,
                'tax_id' => 2,
            ],
            1 => [
                'id' => 2,
                'name' => 'Celeron Silver N5030 15.6"',
                'slug' => 'pentium-silver-n5030-15-6',
                'code' => '000002',
                'model' => 'HP 15s-du1116TU',
                'barcode_symbology' => 'CODE128',
                'tax_type' => 'Inclusive',
                'purchase_price' => 953.03,
                'regular_price' => 1200.0,
                'discount' => null,
                'inventory_count' => 21.0,
                'alert_qty' => 10,
                'note' => '',
                'status' => 1,
                'image_path' => 'images/products/product-02.jpeg',
                'created_at' => '2022-04-30 22:48:46',
                'updated_at' => '2022-05-01 06:04:24',
                'sub_cat_id' => 1,
                'brand_id' => 1,
                'unit_id' => 1,
                'tax_id' => 2,
            ],
            2 => [
                'id' => 3,
                'name' => 'Inspiron 15 3510 Intel Celeron',
                'slug' => 'inspiron-15-3510-intel-celeron',
                'code' => '000003',
                'model' => 'Inspiron-3510',
                'barcode_symbology' => 'CODE128',
                'tax_type' => 'Exclusive',
                'purchase_price' => 1145.89,
                'regular_price' => 1500.0,
                'discount' => null,
                'inventory_count' => 23.0,
                'alert_qty' => 10,
                'note' => '',
                'status' => 1,
                'image_path' => 'images/products/product-03.jpeg',
                'created_at' => '2022-04-30 22:51:07',
                'updated_at' => '2022-05-01 06:04:24',
                'sub_cat_id' => 1,
                'brand_id' => 2,
                'unit_id' => 1,
                'tax_id' => 3,
            ],
        ]);
    }
}
