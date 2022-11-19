<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoFailedJobsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('failed_jobs')->delete();
    }
}
