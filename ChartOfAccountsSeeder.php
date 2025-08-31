<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountsSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // Assets
            ['account_code' => '1100', 'account_name' => 'Cash', 'account_type' => 'asset', 'parent_id' => null],
            ['account_code' => '1200', 'account_name' => 'Accounts Receivable', 'account_type' => 'asset', 'parent_id' => null],
            ['account_code' => '1300', 'account_name' => 'Inventory', 'account_type' => 'asset', 'parent_id' => null],

            // Liabilities
            ['account_code' => '2100', 'account_name' => 'Accounts Payable', 'account_type' => 'liability', 'parent_id' => null],
            
            // Equity
            ['account_code' => '3100', 'account_name' => 'Owner’s Equity', 'account_type' => 'equity', 'parent_id' => null],

            // Revenue
            ['account_code' => '4100', 'account_name' => 'Sales Revenue', 'account_type' => 'revenue', 'parent_id' => null],

            // Expenses
            ['account_code' => '5100', 'account_name' => 'Cost of Goods Sold', 'account_type' => 'expense', 'parent_id' => null],
            ['account_code' => '5200', 'account_name' => 'Operating Expenses', 'account_type' => 'expense', 'parent_id' => null],
            ['account_code' => '5210', 'account_name' => 'Rent', 'account_type' => 'expense', 'parent_id' => null],
            ['account_code' => '5220', 'account_name' => 'Utilities', 'account_type' => 'expense', 'parent_id' => null],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
