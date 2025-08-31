<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Services\ReportingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->middleware(['auth', 'owner']);
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        return view('owner.reports.index');
    }

    public function profitAndLoss(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $report = $this->reportingService->generateProfitLossStatement($startDate, $endDate);
        
        return view('owner.reports.profit-loss', compact('report', 'startDate', 'endDate'));
    }

    public function investorProfitAndLoss(Request $request, Investor $investor)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $report = $this->reportingService->generateInvestorProfitLoss($investor, $startDate, $endDate);

        return view('owner.reports.investor-profit-loss', compact('report', 'investor', 'startDate', 'endDate'));
    }

    public function arAging(Request $request)
    {
        $agingReport = $this->reportingService->generateAccountsReceivableAging();

        return view('owner.reports.ar-aging', compact('agingReport'));
    }

    public function apAging(Request $request)
    {
        $agingReport = $this->reportingService->generateAccountsPayableAging();

        return view('owner.reports.ap-aging', compact('agingReport'));
    }
}