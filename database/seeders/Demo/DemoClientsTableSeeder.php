<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoClientsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('clients')->delete();

        \DB::table('clients')->insert([
            0 => [
                'id' => 1,
                'name' => 'Walking Customer',
                'client_id' => '1',
                'slug' => 'walking-customer',
                'email' => 'acculance@example.com',
                'phone' => '017000000',
                'company_name' => 'Codeshaper',
                'address' => 'Mohakhali DOHS, Dhaka',
                'status' => 1,
                'image_path' => 'images/clients/avatar.png',
                'created_at' => '2022-04-30 22:42:22',
                'updated_at' => '2022-04-30 22:42:22',
            ],
            1 => [
                'id' => 2,
                'name' => 'Ruth Miles',
                'client_id' => '2',
                'slug' => 'ruth-miles',
                'email' => 'diji@mailinator.com',
                'phone' => '+1 (579) 416-3689',
                'company_name' => 'Woodard Traders',
                'address' => 'Dolores eiusmod Nam',
                'status' => 1,
                'image_path' => 'images/clients/avatar.png',
                'created_at' => '2022-04-30 22:42:43',
                'updated_at' => '2022-04-30 22:42:49',
            ],
            2 => [
                'id' => 3,
                'name' => 'Reed Montoya',
                'client_id' => '3',
                'slug' => 'reed-montoya',
                'email' => 'cilegubapo@mailinator.com',
                'phone' => '+1 (857) 968-5584',
                'company_name' => 'Petersen LLC',
                'address' => 'Consectetur est sed',
                'status' => 1,
                'image_path' => 'images/clients/avatar.png',
                'created_at' => '2022-04-30 22:43:20',
                'updated_at' => '2022-04-30 22:43:20',
            ],
            3 => [
                'id' => 4,
                'name' => 'Ciaran Buck',
                'client_id' => '4',
                'slug' => 'ciaran-buck',
                'email' => 'becubahyfi@mailinator.com',
                'phone' => '+1 (898) 921-8127',
                'company_name' => 'Ballard Rosa Plc',
                'address' => 'Aut odio natus ipsum',
                'status' => 1,
                'image_path' => 'images/clients/avatar.png',
                'created_at' => '2022-04-30 22:43:40',
                'updated_at' => '2022-04-30 22:43:40',
            ],
            4 => [
                'id' => 5,
                'name' => 'Troy Walker',
                'client_id' => '5',
                'slug' => 'troy-walker',
                'email' => 'namygyjyxe@mailinator.com',
                'phone' => '+1 (642) 971-4148',
                'company_name' => 'Velasquez Plc',
                'address' => 'Consequatur voluptat',
                'status' => 1,
                'image_path' => 'images/clients/avatar.png',
                'created_at' => '2022-04-30 22:44:14',
                'updated_at' => '2022-05-01 05:06:31',
            ],
        ]);
    }
}
