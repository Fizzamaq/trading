<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpAndSupportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index()
    {
        return view('owner.help-and-support.index');
    }
}