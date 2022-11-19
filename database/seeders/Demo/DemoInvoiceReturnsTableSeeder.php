<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoInvoiceReturnsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoice_returns')->delete();

        \DB::table('invoice_returns')->insert([
            0 => [
                'id' => 1,
                'return_no' => '1',
                'reason' => 'Product Damage',
                'slug' => 'product-damage',
                'total_return' => 3745.0,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'created_at' => '2022-05-01 05:08:14',
                'updated_at' => '2022-05-01 05:08:14',
                'invoice_id' => 4,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'return_no' => '2',
                'reason' => 'Product damage',
                'slug' => 'product-damage-2',
                'total_return' => 11235.0,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-05-01 05:10:01',
                'updated_at' => '2022-05-01 05:10:01',
                'invoice_id' => 6,
                'transaction_id' => 17,
                'created_by' => 1,
            ],
        ]);
    }
}
