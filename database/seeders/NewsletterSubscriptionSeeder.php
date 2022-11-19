<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscription;
use Faker\Factory;
use Illuminate\Database\Seeder;

class NewsletterSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            NewsletterSubscription::create([
                'email' => $faker->email,
            ]);
        }
    }
}
