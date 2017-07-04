<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index()
    {
        $membership_monthly = Setting::where('key', 'membership_monthly')->first()->toArray();
        $membership_daily = Setting::where('key', 'membership_daily')->first()->toArray();

        $activeMembers = Member::active()->get()->toArray();
        $inactiveMembers = Member::inactive()->get()->toArray();

        dd(array_merge($activeMembers, $inactiveMembers, [$membership_monthly, $membership_daily]));

        return view('members.index');
    }
}
