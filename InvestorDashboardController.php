<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\InvestorRequest;
use App\Services\ProfitDistributionService;
use Illuminate\Http\Request;

class InvestorDashboardController extends Controller
{
    protected $profitDistributionService;

    public function __construct(ProfitDistributionService $profitDistributionService)
    {
        $this->middleware(['auth', 'investor', 'investor.onboarding']);
        $this->profitDistributionService = $profitDistributionService;
    }

    public function index()
    {
        $user = auth()->user();
        $investor = $user->investor;

        if (!$investor) {
            return redirect()->route('login')->with('error', 'Investor record not found.');
        }

        // Get profit summary
        $profitSummary = $this->profitDistributionService->getInvestorProfitSummary($investor->id);

        // Recent profit distributions
        $recentDistributions = $investor->profitDistributions()
            ->with('monthlyProfit')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent requests
        $recentRequests = $investor->requests()
            ->orderBy('requested_at', 'desc')
            ->limit(5)
            ->get();

        return view('investor.dashboard', compact(
            'investor',
            'profitSummary',
            'recentDistributions',
            'recentRequests'
        ));
    }

    public function createRequest()
    {
        $investor = auth()->user()->investor;
        $availableProfit = $investor->available_profit;

        return view('investor.create-request', compact('investor', 'availableProfit'));
    }

    public function storeRequest(Request $request)
    {
        $investor = auth()->user()->investor;
        
        $request->validate([
            'request_type' => 'required|in:withdrawal,reinvestment',
            'amount' => 'required|numeric|min:100|max:' . $investor->available_profit,
            'reason' => 'nullable|string|max:1000',
        ]);

        $investorRequest = InvestorRequest::create([
            'investor_id' => $investor->id,
            'request_type' => $request->request_type,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Send notification to owner
        app(\App\Services\NotificationService::class)
            ->notifyProfitWithdrawalRequest($investorRequest);

        return redirect()->route('investor.dashboard')
            ->with('success', 'Request submitted successfully!');
    }
}
