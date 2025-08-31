<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'ABC Textile Mills',
                'contact_person' => 'Ahmed Khan',
                'phone' => '+92-300-1234567',
                'email' => 'ahmed@abctextile.com',
                'address' => 'Industrial Area, Faisalabad, Pakistan',
                'credit_limit' => 500000.00,
                'credit_days' => 30,
            ],
            [
                'name' => 'Quality Cloth Suppliers',
                'contact_person' => 'Fatima Sheikh',
                'phone' => '+92-321-9876543',
                'email' => 'fatima@qualitycloth.com',
                'address' => 'Textile Market, Karachi, Pakistan',
                'credit_limit' => 300000.00,
                'credit_days' => 45,
            ],
            [
                'name' => 'Premium Fabrics Ltd',
                'contact_person' => 'Muhammad Ali',
                'phone' => '+92-333-5555555',
                'email' => 'ali@premiumfabrics.com',
                'address' => 'Anarkali Bazaar, Lahore, Pakistan',
                'credit_limit' => 750000.00,
                'credit_days' => 60,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
