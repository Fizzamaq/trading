<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Investor;

class InvestorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $investor = Investor::where('user_id', $user->id)->first();
        
        $totalInvestment = $investor->investment_amount ?? 50000;
        $ownershipPercentage = $investor->ownership_percentage ?? 5.0;
        $monthlyReturns = $totalInvestment * 0.0125; // 1.25% monthly return
        $totalEarnings = $monthlyReturns * 12; // Assuming 1 year of returns
        
        return view('investor.dashboard', compact(
            'totalInvestment',
            'ownershipPercentage', 
            'monthlyReturns',
            'totalEarnings'
        ));
    }
}
