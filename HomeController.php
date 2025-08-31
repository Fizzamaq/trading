<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        } elseif ($user->hasRole('director')) {
            return redirect()->route('director.dashboard');
        } elseif ($user->hasRole('investor')) {
            return redirect()->route('investor.dashboard');
        }

        return view('home'); // fallback generic view
    }
}
