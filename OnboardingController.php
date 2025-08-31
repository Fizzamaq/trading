<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OnboardingController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth', 'investor']);
    }

    public function show()
    {
        $user = Auth::user();
        $investor = $user->investor;

        if ($investor && $investor->onboarding_completed) {
            return redirect()->route('investor.dashboard');
        }

        return view('auth.onboarding', compact('user', 'investor'));
    }

    public function complete(Request $request)
    {
        $user = Auth::user();
        $investor = $user->investor;

        if (!$investor) {
            return redirect()->route('login')->with('error', 'Investor record not found.');
        }

        if ($investor->onboarding_completed) {
            return redirect()->route('investor.dashboard');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'bank_account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:255',
            'declaration_signed' => 'required|accepted',
        ]);

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Update investor information
        $investor->update([
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'declaration_signed' => true,
            'declaration_signed_at' => now(),
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ]);

        // Log the onboarding completion
        $this->activityLogService->logActivity([
            'user_id' => $user->id,
            'action' => 'onboarding_completed',
            'model_type' => 'App\\\\Models\\\\Investor',
            'model_id' => $investor->id,
        ]);

        return redirect()->route('investor.dashboard')->with('success', 'Onboarding completed successfully!');
    }
}
