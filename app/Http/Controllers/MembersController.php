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

    public function getNewMember()
    {
        return view('members.new');
    }

    public function postNewMember(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'address' => 'max:250',
            'phone' => 'max:50',
            'joined_at' => 'required|date',
        ]);

        Member::create([
            'name' => $request->name,
            'address' => $request->address ?: null,
            'phone' => $request->phone ?: null,
            'joined_at' => $request->joined_at,
            'active' => $request->active ? 1 : 0,
        ]);

        return redirect()->route('members.index');
    }

    public function postNewPayment(Request $request, Member $member)
    {
        $this->validate($request, [
            'value' => 'required|integer|digits_between:1,3',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date',
        ]);

        $newPayment = $member->payments()->create([
            'value' => $request->value,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
        ]);

        return response()->json([
            'data' => $newPayment,
        ]);
    }
}
