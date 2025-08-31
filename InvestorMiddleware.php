<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'investor') {
            abort(403, 'Access denied. Investor role required.');
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact administrator.');
        }

        return $next($request);
    }
}
