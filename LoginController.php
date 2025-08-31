<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact administrator.',
                ]);
            }

            // Log the login event
            $this->activityLogService->logUserLogin($user);

            $request->session()->regenerate();

            switch ($user->role) {
                case 'owner':
                    return redirect()->intended(route('owner.dashboard'));
                case 'director':
                    return redirect()->intended(route('director.dashboard'));
                case 'investor':
                    if ($user->investor && !$user->investor->onboarding_completed) {
                        return redirect()->route('investor.onboarding');
                    }
                    return redirect()->intended(route('investor.dashboard'));
                default:
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Invalid user role.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->activityLogService->logUserLogout($user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
