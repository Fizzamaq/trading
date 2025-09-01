<?php

namespace App\Services;

use App\Models\MonthlyProfit;
use App\Models\SalesInvoice;
use App\Models\Expense;
use App\Models\Investor;
use App\Models\InvestorProfitDistribution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitDistributionService
{
    /**
     * Distributes the monthly profit among all active investors.
     * This process is triggered when a monthly cycle completes.
     *
     * @param string $month The month for which to distribute profit (YYYY-MM-DD format).
     * @return MonthlyProfit
     * @throws \Exception
     */
    public function distributeMonthlyProfit(string $month): MonthlyProfit
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // Check if profit is already distributed for this month
        if (MonthlyProfit::where('profit_month', $startOfMonth)->exists()) {
            throw new \Exception('Profit for this month has already been distributed.');
        }

        // Get all paid sales and expenses for the month
        $paidSales = SalesInvoice::where('status', 'paid')
            ->whereBetween('settled_date', [$startOfMonth, $endOfMonth])
            ->get();

        $monthlyExpenses = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $totalSales = $paidSales->sum('total_amount');
        $totalCogs = $paidSales->sum('cost_of_goods');
        $netProfit = $totalSales - $totalCogs - $monthlyExpenses;

        if ($netProfit <= 0) {
            throw new \Exception('No net profit to distribute for this month.');
        }
        
        $activeInvestors = Investor::whereHas('user', function($query) {
            $query->where('status', 'active');
        })->get();

        $totalShares = $activeInvestors->sum('total_shares');

        if ($totalShares == 0) {
            // If there are no active investors, all profit goes to the owner.
            $monthlyProfit = MonthlyProfit::create([
                'profit_month' => $startOfMonth,
                'total_sales' => $totalSales,
                'total_cogs' => $totalCogs,
                'gross_profit' => $totalSales - $totalCogs,
                'total_expenses' => $monthlyExpenses,
                'net_profit' => $netProfit,
                'total_investor_shares' => 0,
                'profit_per_share' => 0,
                'distribution_completed' => true,
                'distribution_date' => now(),
            ]);

            return $monthlyProfit;
        }

        // Calculate profit per share
        $profitPerShare = $netProfit / $totalShares;

        // Create the MonthlyProfit record
        $monthlyProfit = MonthlyProfit::create([
            'profit_month' => $startOfMonth,
            'total_sales' => $totalSales,
            'total_cogs' => $totalCogs,
            'gross_profit' => $totalSales - $totalCogs,
            'total_expenses' => $monthlyExpenses,
            'net_profit' => $netProfit,
            'total_investor_shares' => $totalShares,
            'profit_per_share' => $profitPerShare,
            'distribution_completed' => true,
            'distribution_date' => now(),
        ]);
        
        // Distribute profit to each investor
        foreach ($activeInvestors as $investor) {
            $shareOfProfit = $investor->total_shares * $profitPerShare;
            $investorPortion = $shareOfProfit * ($investor->profit_percentage / 100);
            $ownerPortion = $shareOfProfit - $investorPortion;

            InvestorProfitDistribution::create([
                'monthly_profit_id' => $monthlyProfit->id,
                'investor_id' => $investor->id,
                'shares_held' => $investor->total_shares,
                'share_of_profit' => $shareOfProfit,
                'investor_portion' => $investorPortion,
                'owner_portion' => $ownerPortion,
                'status' => 'available'
            ]);
        }
        
        return $monthlyProfit;
    }

    /**
     * Get a summary of profit and distributions for a specific investor.
     *
     * @param int $investorId
     * @return array
     */
    public function getInvestorProfitSummary(int $investorId): array
    {
        $investor = Investor::findOrFail($investorId);

        // Sum up all distributed profit portions for the investor
        $totalEarnings = InvestorProfitDistribution::where('investor_id', $investorId)
            ->sum('investor_portion');

        // Sum up all withdrawals and reinvestments
        $totalWithdrawn = $investor->requests()->where('request_type', 'withdrawal')->where('status', 'completed')->sum('amount');
        $totalReinvested = $investor->requests()->where('request_type', 'reinvestment')->where('status', 'completed')->sum('amount');

        // Calculate the available profit
        $availableProfit = $totalEarnings - $totalWithdrawn;

        return [
            'totalInvestment' => $investor->investment_amount,
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $totalWithdrawn,
            'totalReinvested' => $totalReinvested,
            'availableProfit' => $availableProfit,
        ];
    }
}
