<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoPaymentMethodsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_methods')->delete();

        \DB::table('payment_methods')->insert([
            0 => [
                'id' => 1,
                'name' => 'Cash Payment',
                'slug' => 'cash-payment',
                'code' => 'Cash',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-04-30 22:18:30',
                'updated_at' => '2022-04-30 22:18:30',
            ],
            1 => [
                'id' => 2,
                'name' => 'Account Payment',
                'slug' => 'account-payment',
                'code' => 'Account',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-04-30 22:18:44',
                'updated_at' => '2022-04-30 22:18:44',
            ],
            2 => [
                'id' => 3,
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'code' => 'Bank Transfer',
                'note' => null,
                'status' => 1,
                'created_at' => '2022-04-30 22:18:54',
                'updated_at' => '2022-04-30 22:18:54',
            ],
        ]);
    }
}
