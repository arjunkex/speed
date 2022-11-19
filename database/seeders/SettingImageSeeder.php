<?php

namespace Database\Seeders;

use App\Models\SettingImage;
use Illuminate\Database\Seeder;

class SettingImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $types = ['why_us_cards', 'features', 'explorers', 'all_features', 'software_overview_images', 'testimonials', 'brands'];

        // foreach ($types as $type) {
        //     SettingImage::factory(5)->create([
        //         'type' => $type,
        //     ]);
        // }

        $settingImages = [
            // why us cards
            [
                'type' => 'why_us_cards',
                'image' => 'images/template/Multitenancy.png',
                'title' => 'Multitenancy',
                'description' => 'The multitenancy feature enables the individual database for each individual tenant.',
                'status' => true,
            ],
            [
                'type' => 'why_us_cards',
                'image' => 'images/template/Multilingual.png',
                'title' => 'Multilingual',
                'description' => 'Multilingual feature allows you to use Acculance SaaS in your local language.',
                'status' => true,
            ],
            [
                'type' => 'why_us_cards',
                'image' => 'images/template/SPA.png',
                'title' => 'SPA',
                'description' => 'Acculance SaaS is a Single Page Application that doesn\'t need a page refresh which makes it faster.',
                'status' => true,
            ],
            [
                'type' => 'why_us_cards',
                'image' => 'images/template/Custom Domain.png',
                'title' => 'Custom Domain',
                'description' => 'Acculance SaaS can be used on any custom domain that helps can help you to establish brand identity.',
                'status' => true,
            ],
            // features
            [
                'type' => 'features',
                'image' => 'images/template/Easy POS.png',
                'title' => 'Easy POS',
                'status' => true,
            ],
            [
                'type' => 'features',
                'image' => 'images/template/Barcode Generator.png',
                'title' => 'Barcode Generator',
                'status' => true,
            ],
            [
                'type' => 'features',
                'image' => 'images/template/Role Management.png',
                'title' => 'Role Management',
                'status' => true,
            ],
            [
                'type' => 'features',
                'image' => 'images/template/Reports Insights.png',
                'title' => 'Reports Insights',
                'status' => true,
            ],
            [
                'type' => 'features',
                'image' => 'images/template/Database Backup.png',
                'title' => 'Database Backup',
                'status' => true,
            ],
            [
                'type' => 'features',
                'image' => 'images/template/Style Customizer.png',
                'title' => 'Style Customizer',
                'status' => true,
            ],
            // explorers
            [
                'type' => 'explorers',
                'image' => 'images/template/ms1.png',
                'image_align_left' => true,
                'title' => 'Automate Your Business',
                'description' => '',
                'points' => json_encode([
                    'Access to all modules that you need to automate your business.',
                    'Invite your employees and allow them to access the system.',
                    'Quote, invoice, and get paid for your invoices.',
                ]),
                'button_text' => 'Read More',
                'button_link' => '#',
                'status' => true,
            ],
            [
                'type' => 'explorers',
                'image' => 'images/template/ms2.png',
                'image_align_left' => false,
                'title' => 'Remove Your PaperWork',
                'description' => '',
                'points' => json_encode([
                    'Keep track of all your business activities in the most simple way.',
                    'Keep an eye on your finances day by day using the dashboard.',
                    'Send quotations, invoices to customer email in one click.',
                ]),
                'button_text' => 'Read More',
                'button_link' => '#',
                'status' => true,
            ],
            [
                'type' => 'explorers',
                'image' => 'images/template/ms3.png',
                'image_align_left' => true,
                'title' => 'Go Beyond Your Expectation',
                'description' => '',
                'points' => json_encode([
                    'Get a 360° view of your Business growth on one dashboard.',
                    'Derive intelligent insights about your business with analytics.',
                    'Access the same up-to-date information at the same time.',
                ]),
                'button_text' => 'Read More',
                'button_link' => '#',
                'status' => true,
            ],

            // all_features
            [
                'type' => 'all_features',
                'image' => 'images/template/Customer Management.png',
                'title' => 'Customer Management',
                'description' => 'Easily manage all your customers in one place.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Supplier Management.png',
                'title' => 'Supplier Management',
                'description' => 'Easily manage all your suppliers in one place.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Employee Management.png',
                'title' => 'Employee Management',
                'description' => 'Easily manage all your employees in one place.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Expense Management.png',
                'title' => 'Expense Management',
                'description' => 'Manage your company expenses by category and subcategory.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Purchase Management.png',
                'title' => 'Purchase Management',
                'description' => 'Purchases will automatically increase the product stock in the Inventory.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Quotation Management.png',
                'title' => 'Quotation Management',
                'description' => 'Send quotations to the customers and easily make sales from the quotations.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Sales Management.png',
                'title' => 'Sales Management',
                'description' => 'Easily make sales using the POS and manage sales from invoices.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Loan Management.png',
                'title' => 'Loan Management',
                'description' => 'Manage your term & CC loans easily using the Loan Management Module.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Asset Management.png',
                'title' => 'Asset Management',
                'description' => 'Asset management module helps manage assets including their depreciation.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Payroll Management.png',
                'title' => 'Payroll Management',
                'description' => 'Keep track of employee payroll and easily manage their deduction and increments.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Accounting Management.png',
                'title' => 'Accounting Management',
                'description' => 'Account management help to manage your cash and bank account transactions.',
                'status' => true,
            ],
            [
                'type' => 'all_features',
                'image' => 'images/template/Payments Management.png',
                'title' => 'Payments Management',
                'description' => 'Easily manage customer invoice payments and supplier purchase payments.',
                'status' => true,
            ],
            // software_overview_images
            [
                'type' => 'software_overview_images',
                'image' => 'images/template/software-overview.png',
                'status' => true,
            ],
            [
                'type' => 'software_overview_images',
                'image' => 'images/template/software-overview.png',
                'status' => true,
            ],
            [
                'type' => 'software_overview_images',
                'image' => 'images/template/software-overview.png',
                'status' => true,
            ],
            [
                'type' => 'software_overview_images',
                'image' => 'images/template/software-overview.png',
                'status' => true,
            ],
            [
                'type' => 'software_overview_images',
                'image' => 'images/template/software-overview.png',
                'status' => true,
            ],
            // testimonials
            [
                'type' => 'testimonials',
                'name' => 'John Doe',
                'title' => 'CEO, ABC Company',
                'image' => 'images/template/client1.png',
                'description' => 'The Payroll module helps you manage monthly salaries. Using the payroll module you can generate monthly salaries for employees.',
                'status' => true,
            ],
            [
                'type' => 'testimonials',
                'name' => 'Mark Angelina',
                'title' => 'CEO, ABC Company',
                'image' => 'images/template/client2.png',
                'description' => 'The Payroll module helps you manage monthly salaries. Using the payroll module you can generate monthly salaries for employees.',
                'status' => true,
            ],
            [
                'type' => 'testimonials',
                'name' => 'John Doe',
                'title' => 'CEO, ABC Company',
                'image' => 'images/template/client1.png',
                'description' => 'The Payroll module helps you manage monthly salaries. Using the payroll module you can generate monthly salaries for employees.',
                'status' => true,
            ],
            // brands section
            [
                'type' => 'brands',
                'name' => 'Abc Company',
                'image' => 'images/template/client-logo1.png',
                'status' => true,
            ],
            [
                'type' => 'brands',
                'name' => 'Abc Company',
                'image' => 'images/template/client-logo1.png',
                'status' => true,
            ],
            [
                'type' => 'brands',
                'name' => 'Abc Company',
                'image' => 'images/template/client-logo1.png',
                'status' => true,
            ],
            [
                'type' => 'brands',
                'name' => 'Abc Company',
                'image' => 'images/template/client-logo1.png',
                'status' => true,
            ],
            [
                'type' => 'brands',
                'name' => 'Abc Company',
                'image' => 'images/template/client-logo1.png',
                'status' => true,
            ],
        ];

        foreach ($settingImages as $settingImage) {
            SettingImage::create($settingImage);
        }
    }
}