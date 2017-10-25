<?php

namespace App\Http\Controllers\Members;

use Log;
use App\Models\Member;
use App\Models\MemberPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberPaymentsController extends Controller
{
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

        Log::info('Member payment created', [
            'created_by' => auth()->user()->id . ' ('.auth()->user()->name.')',
            'member' => $member->id . '('.$member->name.')',
            'payment' => [
                'id' => $newPayment->id,
                'value' => $newPayment->value,
                'valid_from' => $newPayment->valid_from,
                'valid_until' => $newPayment->valid_until,
                'description' => $newPayment->description,
            ],
        ]);

        return response()->json([
            'data' => $newPayment,
        ]);
    }

    public function deletePayment(Request $request, Member $member, MemberPayment $payment)
    {
        if(!$member->payments->contains($payment->id)) return response(null, 400);

        $payment->delete();

        Log::info('Member payment deleted', [
            'deleted_by' => auth()->user()->id . ' ('.auth()->user()->name.')',
            'member' => $member->id . '('.$member->name.')',
            'payment' => [
                'id' => $payment->id,
                'value' => $payment->value,
                'valid_from' => $payment->valid_from,
                'valid_until' => $payment->valid_until,
                'description' => $payment->description,
            ],
        ]);

        return response(null, 200);
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
