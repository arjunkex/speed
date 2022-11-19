<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::insert([
            [
                'name'      => 'About Us',
                'slug'      => 'about-us',
                'type'      => 0,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'News',
                'slug'      => 'news',
                'type'      => 0,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'Investor Relations',
                'slug'      => 'investor-relations',
                'type'      => 0,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'Careers',
                'slug'      => 'careers',
                'type'      => 0,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'Contact Us',
                'slug'      => 'contact-us',
                'type'      => 1,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'FAQ',
                'slug'      => 'faq',
                'type'      => 1,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'Refund Policy',
                'slug'      => 'refund-policy',
                'type'      => 1,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
            [
                'name'      => 'Help Docs',
                'slug'      => 'help-docs',
                'type'      => 1,
                'content'   => '<p><br></p>',
                'status'    => 1,
            ],
        ]);
    }
}