<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorOnboardingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Add any investor onboarding checks here if needed
        // For now, just let it pass through
        return $next($request);
    }
}
