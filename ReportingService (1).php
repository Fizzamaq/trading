<?php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Investor;
use App\Models\InvestorProfitDistribution;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    public function generateProfitLossStatement($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Sales Revenue
        $salesRevenue = SalesInvoice::whereBetween('invoice_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('total_amount');

        // Cost of Goods Sold
        $costOfGoodsSold = SalesInvoice::whereBetween('invoice_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('cost_of_goods');

        // Gross Profit
        $grossProfit = $salesRevenue - $costOfGoodsSold;

        // Operating Expenses
        $operatingExpenses = Expense::whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        // Net Profit
        $netProfit = $grossProfit - $operatingExpenses;

        return [
            'period' => [
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'description' => $start->format('F j, Y') . ' to ' . $end->format('F j, Y'),
            ],
            'revenue' => [
                'sales_revenue' => $salesRevenue,
            ],
            'cost_of_sales' => [
                'cost_of_goods_sold' => $costOfGoodsSold,
            ],
            'gross_profit' => $grossProfit,
            'expenses' => [
                'operating_expenses' => $operatingExpenses,
            ],
            'net_profit' => $netProfit,
        ];
    }

    public function generateInvestorSpecificPL($investorId, $startDate, $endDate)
    {
        $investor = Investor::findOrFail($investorId);
        $totalShares = Investor::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->sum('total_shares');

        if ($totalShares == 0) {
            throw new \Exception('No active investor shares found');
        }

        $investorSharePercentage = $investor->total_shares / $totalShares;

        $overallPL = $this->generateProfitLossStatement($startDate, $endDate);

        $investorPL = [
            'investor' => $investor->user->name,
            'shares_held' => $investor->total_shares,
            'total_shares' => $totalShares,
            'share_percentage' => round($investorSharePercentage * 100, 2),
            'period' => $overallPL['period'],
            'revenue' => [
                'sales_revenue' => $overallPL['revenue']['sales_revenue'] * $investorSharePercentage,
            ],
            'cost_of_sales' => [
                'cost_of_goods_sold' => $overallPL['cost_of_sales']['cost_of_goods_sold'] * $investorSharePercentage,
            ],
            'gross_profit' => $overallPL['gross_profit'] * $investorSharePercentage,
            'expenses' => [
                'operating_expenses' => $overallPL['expenses']['operating_expenses'] * $investorSharePercentage,
            ],
            'net_profit' => $overallPL['net_profit'] * $investorSharePercentage,
            'investor_portion' => ($overallPL['net_profit'] * $investorSharePercentage) * ($investor->profit_percentage / 100),
            'owner_portion' => ($overallPL['net_profit'] * $investorSharePercentage) * ((100 - $investor->profit_percentage) / 100),
        ];

        return $investorPL;
    }

    public function generateAccountsReceivableAging()
    {
        $currentDate = now();

        $aging = SalesInvoice::with('customer')
            ->where('status', '!=', 'paid')
            ->selectRaw('
                customer_id,
                invoice_number,
                invoice_date,
                due_date,
                total_amount,
                remaining_amount,
                CASE 
                    WHEN due_date >= CURDATE() THEN "Current"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN "1-30 Days"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY) THEN "31-60 Days"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) THEN "61-90 Days"
                    ELSE "Over 90 Days"
                END as aging_bucket,
                DATEDIFF(CURDATE(), due_date) as days_overdue
            ')
            ->orderBy('customer_id')
            ->orderBy('due_date')
            ->get()
            ->groupBy(['customer.name', 'aging_bucket']);

        return $aging;
    }

    public function generateAccountsPayableAging()
    {
        $currentDate = now();

        $aging = PurchaseInvoice::with('supplier')
            ->where('status', '!=', 'paid')
            ->selectRaw('
                supplier_id,
                invoice_number,
                invoice_date,
                due_date,
                total_amount,
                remaining_amount,
                CASE 
                    WHEN due_date >= CURDATE() THEN "Current"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN "1-30 Days"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY) THEN "31-60 Days"
                    WHEN due_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) THEN "61-90 Days"
                    ELSE "Over 90 Days"
                END as aging_bucket,
                DATEDIFF(CURDATE(), due_date) as days_overdue
            ')
            ->orderBy('supplier_id')
            ->orderBy('due_date')
            ->get()
            ->groupBy(['supplier.name', 'aging_bucket']);

        return $aging;
    }
}
