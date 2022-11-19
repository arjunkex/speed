<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoInvoicePaymentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoice_payments')->delete();

        \DB::table('invoice_payments')->insert([
            0 => [
                'id' => 1,
                'slug' => '626d33294389d',
                'amount' => 15000.0,
                'discount' => null,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-04-30 23:01:29',
                'updated_at' => '2022-04-30 23:01:29',
                'invoice_id' => 1,
                'transaction_id' => 14,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'slug' => '626d86a83b9db',
                'amount' => 20000.0,
                'discount' => null,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => '2022-05-01 04:57:44',
                'updated_at' => '2022-05-01 04:57:44',
                'invoice_id' => 5,
                'transaction_id' => 15,
                'created_by' => 1,
            ],
            2 => [
                'id' => 3,
                'slug' => '626d889df18b1',
                'amount' => 35000.0,
                'discount' => null,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'created_at' => '2022-05-01 05:06:05',
                'updated_at' => '2022-05-01 05:06:05',
                'invoice_id' => 6,
                'transaction_id' => 16,
                'created_by' => 1,
            ],
        ]);
    }
}
