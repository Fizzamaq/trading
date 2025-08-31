<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\ChartOfAccount;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Rent', 'description' => 'Monthly office and warehouse rent', 'account_code' => '5210'],
            ['name' => 'Utilities', 'description' => 'Electricity, gas, water bills', 'account_code' => '5220'],
            ['name' => 'Office Supplies', 'description' => 'Stationery, printing, office equipment', 'account_code' => '5200'],
            ['name' => 'Transportation', 'description' => 'Fuel, vehicle maintenance, delivery costs', 'account_code' => '5200'],
            ['name' => 'Professional Services', 'description' => 'Legal, accounting, consulting fees', 'account_code' => '5200'],
        ];

        foreach ($categories as $category) {
            $account = ChartOfAccount::where('account_code', $category['account_code'])->first();

            ExpenseCategory::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'account_id' => $account ? $account->id : null,
                'is_active' => true,
            ]);
        }
    }
}
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
