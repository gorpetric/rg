<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $membership_monthly = Setting::where('key', 'membership_monthly')->first()->value;
        $membership_daily = Setting::where('key', 'membership_daily')->first()->value;

        return view('home', compact('membership_monthly', 'membership_daily'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function privacy()
    {
        return view('privacy');
    }
}
