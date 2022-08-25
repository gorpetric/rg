<?php

namespace App\Http\Controllers\Members;

use App\Models\Members\Member;
use App\Models\Members\MemberPayment;
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
            'currency' => 'required|exists:currencies,id',
        ]);

        $newPayment = $member->payments()->create([
            'value' => $request->value,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'description' => $request->description,
            'currency_id' => $request->currency,
        ]);

        logdb('Member payment created', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'payment' => [
                'id' => $newPayment->id,
                'value' => $newPayment->value,
                'valid_from' => $newPayment->valid_from,
                'valid_until' => $newPayment->valid_until,
                'description' => $newPayment->description,
                'currency_id' => $newPayment->currency_id,
            ],
        ]);

        $newPayment->load('currency');

        return response()->json([
            'data' => $newPayment,
        ]);
    }

    public function deletePayment(Request $request, Member $member, MemberPayment $payment)
    {
        if(!$member->payments->contains($payment->id)) return response(null, 400);

        $payment->delete();

        logdb('Member payment deleted', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'payment' => $payment->id,
        ]);

        return response(null, 200);
    }
}
