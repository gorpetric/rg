<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index()
    {
        $membership_monthly = Setting::where('key', 'membership_monthly')->first()->value;
        $membership_daily = Setting::where('key', 'membership_daily')->first()->value;

        $activeMembers = Member::active()->get()->toArray();
        $inactiveMembers = Member::inactive()->get()->toArray();

        $data = json_encode([
            'meta' => [
                'membership_monthly' => $membership_monthly,
                'membership_daily' => $membership_daily,
            ],
            'members' => [
                'active' => $activeMembers,
                'inactive' => $inactiveMembers,
            ],
        ]);

        return view('members.index', compact('data'));
    }
}
