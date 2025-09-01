<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\ProfitDistribution;
use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;
use App\Models\MonthlyProfit;
use App\Models\InvestorRequest;
use App\Services\ReportingService;
use App\Services\ProfitDistributionService;
use App\Services\ActivityLogService;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OwnerDashboardController extends Controller
{
    protected $reportingService;
    protected $profitDistributionService;
    protected $activityLogService;
    protected $accountingService;

    public function __construct(ReportingService $reportingService, ProfitDistributionService $profitDistributionService, ActivityLogService $activityLogService, AccountingService $accountingService)
    {
        $this->middleware(['auth', 'owner']);
        $this->reportingService = $reportingService;
        $this->profitDistributionService = $profitDistributionService;
        $this->activityLogService = $activityLogService;
        $this->accountingService = $accountingService;
    }

public function index(Request $request)
{
    $dateRange = $request->get('date_range', 'this_month');
    
    // Set date range
    switch ($dateRange) {
        case 'today':
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            break;
        case 'this_week':
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            break;
        case 'this_year':
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
            break;
        case 'this_month':
        default:
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            break;
    }

    // Business metrics (original attractive stats)
    $totalInvestors = Investor::whereHas('user', function($query) {
        $query->where('status', 'active');
    })->count();

    $totalInvestment = Investor::whereHas('user', function($query) {
        $query->where('status', 'active');
    })->sum('investment_amount');

    $pendingRequests = InvestorRequest::where('status', 'pending')->count();
    $pendingExpenses = Expense::where('status', 'pending')->count();
    
    $totalSales = SalesInvoice::whereBetween('invoice_date', [$startDate, $endDate])
        ->sum('total_amount');

    $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
        ->sum('amount');

    // Calculate profit
    $netProfit = $totalSales - $totalExpenses;

    // P&L Data
    $plData = $this->reportingService->generateProfitLossStatement($startDate, $endDate);

    // Recent activities for dashboard
    $recentSales = SalesInvoice::with('customer')
        ->whereBetween('invoice_date', [$startDate, $endDate])
        ->latest()
        ->limit(5)
        ->get();

    $recentExpenses = Expense::with('category')
        ->whereBetween('expense_date', [$startDate, $endDate])
        ->latest()
        ->limit(5)
        ->get();

    // Monthly profit distributions
    $monthlyProfits = MonthlyProfit::where('distribution_completed', true)
        ->orderBy('profit_month', 'desc')
        ->limit(6)
        ->get();

    return view('owner.dashboard', compact(
        'totalInvestors',
        'totalInvestment',
        'pendingRequests',
        'pendingExpenses',
        'totalSales',
        'totalExpenses',
        'netProfit',
        'plData',
        'recentSales',
        'recentExpenses',
        'monthlyProfits',
        'dateRange'
    ));
}

    public function investors()
    {
        // Get all investors with their user data, paginated
        $investors = Investor::with('user')->paginate(15);
        
        // Remove the dd() and return the proper view
        return view('owner.investors.index', compact('investors'));
    }

    public function approveInvestor($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'Investor approved successfully.');
    }

    public function rejectInvestor($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', 'Investor rejected successfully.');
    }

    public function investorRequests()
    {
        $investorRequests = InvestorRequest::with('investor.user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('owner.investor-requests', compact('investorRequests'));
    }

    public function profitDistribution()
    {
        $currentMonth = now()->startOfMonth();
        $availableMonths = [];

        // Generate last 12 months with distributed flag
        for ($i = 0; $i < 12; $i++) {
            $month = now()->subMonths($i)->startOfMonth();
            $availableMonths[] = [
                'value' => $month->format('Y-m-d'),
                'label' => $month->format('F Y'),
                'distributed' => MonthlyProfit::where('profit_month', $month)->exists(),
            ];
        }

        // Fetch paginated monthly profits with relevant relations
        $monthlyProfits = MonthlyProfit::with('investorDistributions.investor.user')
            ->orderBy('profit_month', 'desc')
            ->paginate(10);

        // Calculate totalProfit as sum of net_profit where distribution not completed
        $totalProfit = MonthlyProfit::where('distribution_completed', 0)->sum('net_profit');

        // Calculate distributedAmount as sum of net_profit where distribution completed
        $distributedAmount = MonthlyProfit::where('distribution_completed', 1)->sum('net_profit');

        // Fetch paginated distribution history
        $distributionHistory = ProfitDistribution::with('adminUser')->paginate(10);

        return view('owner.profit-distribution', compact(
            'availableMonths',
            'monthlyProfits',
            'totalProfit',
            'distributedAmount',
            'distributionHistory'
        ));
    }

    public function distributeProfit(Request $request)
    {
        $request->validate([
            'month' => 'required|date',
        ]);

        try {
            $monthlyProfit = $this->profitDistributionService->distributeMonthlyProfit($request->month);
            return redirect()->back()->with('success', 'Profit distributed successfully for ' . Carbon::parse($request->month)->format('F Y'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to distribute profit: ' . $e->getMessage());
        }
    }

    public function exportProfitReport()
    {
        $monthlyProfits = MonthlyProfit::with('investorDistributions.investor.user')
            ->orderBy('profit_month', 'desc')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="profit_distribution_report_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($monthlyProfits) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Month', 'Net Profit', 'Total Investors', 'Distributed Date']);

            // CSV Data
            foreach ($monthlyProfits as $profit) {
                fputcsv($file, [
                    $profit->profit_month->format('F Y'),
                    $profit->net_profit,
                    $profit->investorDistributions->count(),
                    $profit->distribution_date ? $profit->distribution_date->format('Y-m-d') : 'N/A',
                ]);
            }
            fclose($file);
        };
        
        return new StreamedResponse($callback, 200, $headers);
    }

    public function expenseRequests()
    {
        $pendingExpenses = Expense::with('category', 'creator')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.expense-requests', compact('pendingExpenses'));
    }

    public function approveExpense(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Expense has already been processed.');
        }

        $original = $expense->getOriginal();
        $expense->status = 'approved';
        $expense->save();

        // Log the approval action
        $this->activityLogService->logModelUpdated($expense, $original);
        
        // This is the point where the accounting transaction is created
        $this->accountingService->createExpenseTransaction($expense);

        return redirect()->route('owner.expense.requests')
            ->with('success', 'Expense approved successfully.');
    }

    public function rejectExpense(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Expense has already been processed.');
        }
        
        $original = $expense->getOriginal();
        $expense->status = 'rejected';
        $expense->save();

        // Log the rejection action
        $this->activityLogService->logModelUpdated($expense, $original);

        return redirect()->route('owner.expense.requests')
            ->with('success', 'Expense rejected.');
    }
}
