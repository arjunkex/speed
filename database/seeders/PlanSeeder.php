<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::insert([
            [
                'api_id' => '14103277061654597915',
                'name' => 'New Business',
                'amount' => 19,
                'currency' => 'usd',
                'interval' => 'month',
                'product_id' => 'prod_Lpf1G8TRevH1oR',
                'description' => 'Best for small businesses',
                'limit_clients' => 10,
                'limit_suppliers' => 10,
                'limit_employees' => 5,
                'limit_domains' => 2,
                'limit_purchases' => 999,
                'limit_invoices' => 999,
            ], [
                'api_id' => '8440935411654597938',
                'name' => 'Growing Business',
                'amount' => 49,
                'currency' => 'usd',
                'interval' => 'month',
                'product_id' => 'prod_Lpf14k7ZllQZcq',
                'description' => 'Best for medium businesses',
                'limit_clients' => 100,
                'limit_suppliers' => 100,
                'limit_employees' => 10,
                'limit_domains' => 5,
                'limit_purchases' => 9999,
                'limit_invoices' => 9999,
            ], [
                'api_id' => 'price_1LWfxlFAlaKtQaWMHYhielax',
                'name' => 'Pro Marketer',
                'amount' => 99,
                'currency' => 'usd',
                'interval' => 'month',
                'description' => 'Best for large businesses',
                'product_id' => 'prod_MFAIqugNoFKI4k',
                'limit_clients' => 0,
                'limit_suppliers' => 0,
                'limit_employees' => 0,
                'limit_domains' => 0,
                'limit_purchases' => 0,
                'limit_invoices' => 0,
            ],
        ]);
    }
}