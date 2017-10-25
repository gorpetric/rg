<?php

namespace App\Http\Controllers\Members;

use Log;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewEditMemberRequest;

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

    public function postNewMember(NewEditMemberRequest $request)
    {
        $member = Member::create([
            'name' => $request->name,
            'address' => $request->address ?: null,
            'phone' => $request->phone ?: null,
            'sex' => $request->sex,
            'joined_at' => $request->joined_at,
            'active' => $request->active ? 1 : 0,
        ]);

        Log::info('Member created', [
            'created_by' => auth()->user()->id . ' ('.auth()->user()->name.')',
            'member' => $member->id . '('.$member->name.')',
        ]);

        return response()->json([
            'data' => $member,
        ]);
    }

    public function postEditMember(NewEditMemberRequest $request, Member $member)
    {
        $member->update([
            'name' => $request->name,
            'address' => $request->address ?: null,
            'phone' => $request->phone ?: null,
            'sex' => $request->sex,
            'active' => $request->active ? 1 : 0,
            'joined_at' => $request->joined_at,
        ]);

        Log::info('Member edited', [
            'edited_by' => auth()->user()->id . ' ('.auth()->user()->name.')',
            'member' => $member->id . '('.$member->name.')',
        ]);

        return response()->json([
            'data' => $member,
        ]);
    }
}
