<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorOnboarding
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->isInvestor() && $user->investor) {
            if (!$user->investor->onboarding_completed) {
                return redirect()->route('investor.onboarding');
            }
        }

        return $next($request);
    }
}
