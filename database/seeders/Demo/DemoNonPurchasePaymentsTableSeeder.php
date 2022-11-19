<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoNonPurchasePaymentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('non_purchase_payments')->delete();

        \DB::table('non_purchase_payments')->insert([
            0 => [
                'id' => 1,
                'slug' => '626d8a3c9f0d2',
                'amount' => 20000.0,
                'type' => 0,
                'date' => '2022-04-30',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-05-01 05:13:00',
                'updated_at' => '2022-05-01 05:13:00',
                'supplier_id' => 1,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'slug' => '626d8a5278c92',
                'amount' => 10000.0,
                'type' => 1,
                'date' => '2022-04-30',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-05-01 05:13:22',
                'updated_at' => '2022-05-01 05:13:22',
                'supplier_id' => 1,
                'transaction_id' => 20,
                'created_by' => 1,
            ],
            2 => [
                'id' => 3,
                'slug' => '626d8a5f74ca7',
                'amount' => 15000.0,
                'type' => 0,
                'date' => '2022-04-30',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-05-01 05:13:35',
                'updated_at' => '2022-05-01 05:13:35',
                'supplier_id' => 2,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            3 => [
                'id' => 4,
                'slug' => '626d8a78ece00',
                'amount' => 5000.0,
                'type' => 1,
                'date' => '2022-04-30',
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-05-01 05:14:00',
                'updated_at' => '2022-05-01 05:14:00',
                'supplier_id' => 2,
                'transaction_id' => 21,
                'created_by' => 1,
            ],
        ]);
    }
}
