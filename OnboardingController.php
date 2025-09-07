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

    public function show(Request $request)
    {
        $user = Auth::user();
        $investor = $user->investor;

        if ($investor && $investor->onboarding_completed) {
            return redirect()->route('investor.dashboard');
        }
        
        $step = $request->input('step', 1);

        $onboardingData = $request->session()->get('onboarding_data', []);

        return view('auth.onboarding', compact('user', 'investor', 'step', 'onboardingData'));
    }
    
    public function completeStep1(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $request->session()->put('onboarding_data.password', Hash::make($request->password));
        
        return redirect()->route('investor.onboarding.show', ['step' => 2]);
    }
    
    public function completeStep2(Request $request)
    {
        $request->validate([
            'bank_account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:255',
        ]);
        
        $onboardingData = $request->session()->get('onboarding_data', []);
        $onboardingData['bank_account_number'] = $request->bank_account_number;
        $onboardingData['bank_name'] = $request->bank_name;
        $onboardingData['account_holder_name'] = $request->account_holder_name;
        
        $request->session()->put('onboarding_data', $onboardingData);
        
        return redirect()->route('investor.onboarding.show', ['step' => 3]);
    }

    public function completeStep3(Request $request)
    {
        $user = Auth::user();
        $investor = $user->investor;
        
        $request->validate([
            'declaration_signed' => 'required|accepted',
        ]);

        $onboardingData = $request->session()->get('onboarding_data', []);

        if (empty($onboardingData['password'])) {
            return redirect()->route('investor.onboarding.show', ['step' => 1])->withErrors(['error' => 'Please start from step 1.']);
        }
        
        // Finalize all updates using data from the session
        $user->update([
            'password' => $onboardingData['password'],
        ]);

        $investor->update([
            'bank_account_number' => $onboardingData['bank_account_number'],
            'bank_name' => $onboardingData['bank_name'],
            'account_holder_name' => $onboardingData['account_holder_name'],
            'declaration_signed' => true,
            'declaration_signed_at' => now(),
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ]);
        
        // Clear session data
        $request->session()->forget('onboarding_data');

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
