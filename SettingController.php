<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index()
    {
        // For a full implementation, you would fetch settings from a database table
        $settings = [
            'app_name' => config('app.name'),
            'session_lifetime' => config('session.lifetime'),
            'mail_from_address' => config('mail.from.address'),
        ];
        return view('owner.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'session_lifetime' => 'required|integer|min:1',
            'mail_from_address' => 'required|email|max:255',
        ]);
        
        // In a real application, you would update the database or .env file here.
        // For now, we'll just return a success message.
        
        return redirect()->route('owner.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}