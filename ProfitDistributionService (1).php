<?php

namespace App\Services;

use App\Models\MonthlyProfit;
use App\Models\InvestorProfitDistribution;
use App\Models\SalesInvoice;
use App\Models\Expense;
use App\Models\Investor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfitDistributionService
{
    public function calculateMonthlyProfit($month)
    {
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // Calculate total sales from paid invoices
        $salesData = SalesInvoice::whereBetween('settled_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->where('profit_distributed', false)
            ->selectRaw('
                SUM(total_amount) as total_sales,
                SUM(cost_of_goods) as total_cogs,
                SUM(gross_profit) as gross_profit
            ')
            ->first();

        // Calculate total expenses for the month
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate net profit
        $grossProfit = $salesData->gross_profit ?? 0;
        $netProfit = $grossProfit - $totalExpenses;

        return [
            'total_sales' => $salesData->total_sales ?? 0,
            'total_cogs' => $salesData->total_cogs ?? 0,
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];
    }

    public function distributeMonthlyProfit($month)
    {
        DB::beginTransaction();

        try {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();

            $profitData = $this->calculateMonthlyProfit($month);

            if ($profitData['net_profit'] <= 0) {
                throw new \Exception('No profit to distribute for this month');
            }

            $activeInvestors = Investor::whereHas('user', function ($query) {
                $query->where('status', 'active');
            })->where(function ($query) use ($endDate) {
                $query->whereNull('grace_period_start')
                      ->orWhere('grace_period_start', '<=', $endDate);
            })->get();

            $totalShares = $activeInvestors->sum('total_shares');

            if ($totalShares == 0) {
                throw new \Exception('No active investors with shares found');
            }

            $profitPerShare = $profitData['net_profit'] / $totalShares;

            $monthlyProfit = MonthlyProfit::create([
                'profit_month' => $startDate,
                'total_sales' => $profitData['total_sales'],
                'total_cogs' => $profitData['total_cogs'],
                'gross_profit' => $profitData['gross_profit'],
                'total_expenses' => $profitData['total_expenses'],
                'net_profit' => $profitData['net_profit'],
                'total_investor_shares' => $totalShares,
                'profit_per_share' => $profitPerShare,
                'distribution_completed' => true,
                'distribution_date' => now()->toDateString(),
            ]);

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
                    'status' => 'available',
                ]);
            }

            SalesInvoice::whereBetween('settled_date', [$startDate, $endDate])
                ->where('status', 'paid')
                ->where('profit_distributed', false)
                ->update([
                    'profit_distributed' => true,
                    'profit_distribution_date' => now()->toDateString(),
                ]);

            DB::commit();

            Log::info('Monthly profit distribution completed', [
                'month' => $month,
                'net_profit' => $profitData['net_profit'],
                'total_investors' => $activeInvestors->count(),
                'total_shares' => $totalShares,
            ]);

            return $monthlyProfit;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Monthly profit distribution failed', [
                'month' => $month,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getInvestorProfitSummary($investorId)
    {
        $investor = Investor::findOrFail($investorId);

        return [
            'available_profit' => $investor->profitDistributions()
                ->where('status', 'available')
                ->sum('investor_portion'),
            'pending_profit' => $investor->profitDistributions()
                ->where('status', 'pending')
                ->sum('investor_portion'),
            'total_withdrawn' => $investor->profitDistributions()
                ->where('status', 'withdrawn')
                ->sum('investor_portion'),
            'total_reinvested' => $investor->profitDistributions()
                ->where('status', 'reinvested')
                ->sum('investor_portion'),
        ];
    }
}
