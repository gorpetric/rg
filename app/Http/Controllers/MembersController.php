<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use App\Models\MemberPayment;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index()
    {
        return view('members.index');
    }

    public function getData()
    {
        $membership_monthly = Setting::where('key', 'membership_monthly')->first()->value;
        $membership_daily = Setting::where('key', 'membership_daily')->first()->value;

        $activeMembers = Member::active()->get()->toArray();
        $inactiveMembers = Member::inactive()->get()->toArray();

        $data = [
            'meta' => [
                'membership_monthly' => $membership_monthly,
                'membership_daily' => $membership_daily,
            ],
            'members' => [
                'active' => $activeMembers,
                'inactive' => $inactiveMembers,
            ],
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function postNewMember(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'address' => 'max:250',
            'phone' => 'max:50',
            'sex' => 'required|in:M,F',
            'joined_at' => 'required|date',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'address' => $request->address ?: null,
            'phone' => $request->phone ?: null,
            'sex' => $request->sex,
            'joined_at' => $request->joined_at,
            'active' => $request->active ? 1 : 0,
        ]);

        return response()->json([
            'data' => $member,
        ]);
    }

    public function postEditMember(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'address' => 'max:250',
            'phone' => 'max:50',
            'sex' => 'required|in:M,F',
            'joined_at' => 'required|date',
        ]);

        $member->update([
            'name' => $request->name,
            'address' => $request->address ?: null,
            'phone' => $request->phone ?: null,
            'sex' => $request->sex,
            'active' => $request->active ? 1 : 0,
            'joined_at' => $request->joined_at,
        ]);

        return response()->json([
            'data' => $member,
        ]);
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
            'description' => $request->description,
        ]);

        return response()->json([
            'data' => $newPayment,
        ]);
    }

    public function getMonthlyStats(Request $request)
    {
        $this->validate($request, [
            'month' => 'required|numeric|digits:2|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'year' => 'required|numeric|digits:4',
        ]);

        $data = [];
        $total = 0;

        $payments = MemberPayment::whereYear('valid_from', $request->year)->whereMonth('valid_from', $request->month)->get();
        foreach($payments as $payment) {
            $data['payments'][] = $payment->load('member');
            $total += $payment->value;
        }

        if(!empty($data)) $data['total'] = $total;

        return response()->json([
            'data' => $data,
        ]);
    }
}
