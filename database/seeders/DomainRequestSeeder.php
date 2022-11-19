<?php

namespace Database\Seeders;

use App\Models\DomainRequest;
use Illuminate\Database\Seeder;

class DomainRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DomainRequest::truncate();

        DomainRequest::factory(20)->create();
    }
}
