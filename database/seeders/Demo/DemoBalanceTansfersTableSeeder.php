<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoBalanceTansfersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('balance_tansfers')->delete();

        \DB::table('balance_tansfers')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Office Cash Transfer',
                'slug' => 'office-cash-transfer',
                'amount' => 20000.0,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:27:56',
                'updated_at' => '2022-04-30 22:27:56',
                'debit_id' => 4,
                'credit_id' => 5,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'reason' => 'Regular Transfer',
                'slug' => 'regular-transfer',
                'amount' => 10000.0,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'created_at' => '2022-04-30 22:28:30',
                'updated_at' => '2022-04-30 22:28:30',
                'debit_id' => 6,
                'credit_id' => 7,
                'created_by' => 1,
            ],
        ]);
    }
}
