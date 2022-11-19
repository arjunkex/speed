<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoLoansTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('loans')->delete();

        \DB::table('loans')->insert([
            0 => [
                'id' => 1,
                'reason' => 'business invest',
                'slug' => 'dbbl-0001',
                'reference_no' => 'DBBL-0001',
                'loan_type' => 1,
                'interest' => 8.0,
                'payable' => 108545.52,
                'payment_type' => 1,
                'duration' => 24,
                'date' => today()->subMonths(2),
                'note' => '',
                'status' => 1,
                'is_paid' => 0,
                'image_path' => '',
                'created_at' => '2022-05-01 05:52:01',
                'updated_at' => '2022-05-01 05:52:01',
                'authority_id' => 2,
                'transaction_id' => 24,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'reason' => 'Business invest',
                'slug' => 'ibbl-0001',
                'reference_no' => 'IBBL-0001',
                'loan_type' => 0,
                'interest' => 0.0,
                'payable' => 100000.0,
                'payment_type' => 1,
                'duration' => 0,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'is_paid' => 0,
                'image_path' => '',
                'created_at' => '2022-05-01 05:52:35',
                'updated_at' => '2022-05-01 05:53:06',
                'authority_id' => 1,
                'transaction_id' => 25,
                'created_by' => 1,
            ],
        ]);
    }
}
