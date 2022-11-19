<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoPurchaseReturnsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_returns')->delete();

        \DB::table('purchase_returns')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Broken product',
                'slug' => 'product-expired',
                'code' => 1,
                'total_return' => 5960.0,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-04-30 22:58:16',
                'updated_at' => '2022-04-30 22:58:43',
                'purchase_id' => 1,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'reason' => 'Damage product',
                'slug' => 'damage-product',
                'code' => 2,
                'total_return' => 14900.0,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-04-30 22:59:28',
                'updated_at' => '2022-04-30 22:59:28',
                'purchase_id' => 2,
                'transaction_id' => 13,
                'created_by' => 1,
            ],
        ]);
    }
}
