<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Fashion House Pvt Ltd',
                'contact_person' => 'Ayesha Khan',
                'phone' => '+92-300-7777777',
                'email' => 'ayesha@fashionhouse.com',
                'address' => 'MM Alam Road, Lahore, Pakistan',
                'credit_limit' => 200000.00,
                'credit_days' => 30,
            ],
            [
                'name' => 'Retail Garments Co',
                'contact_person' => 'Usman Ahmed',
                'phone' => '+92-321-8888888',
                'email' => 'usman@retailgarments.com',
                'address' => 'Saddar, Karachi, Pakistan',
                'credit_limit' => 150000.00,
                'credit_days' => 15,
            ],
            [
                'name' => 'Export Quality Ltd',
                'contact_person' => 'Sana Malik',
                'phone' => '+92-333-9999999',
                'email' => 'sana@exportquality.com',
                'address' => 'Export Processing Zone, Sialkot, Pakistan',
                'credit_limit' => 400000.00,
                'credit_days' => 45,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
