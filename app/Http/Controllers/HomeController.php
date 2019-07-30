<?php

namespace App\Http\Controllers;

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

    public function privacy()
    {
        return view('privacy');
    }
}
