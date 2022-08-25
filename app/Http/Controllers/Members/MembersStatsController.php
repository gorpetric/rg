<?php

namespace App\Http\Controllers\Members;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Members\MemberPayment;

class MembersStatsController extends Controller
{
    public function index()
    {
        return view('members.stats');
    }

    public function getStats(Request $request)
    {
        $this->validate($request, [
            'type' => [
                'required',
                Rule::in(['by-month', 'monthly', 'yearly']),
            ],
            'month' => 'required|numeric|digits:2|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'year' => 'required|numeric|digits:4',
        ]);

        $data = [];

        switch($request->type) {
            case 'by-month':
                $data = $this->getStatsByMonth($request);
                break;
            case 'monthly':
                $data = $this->getStatsMonthly($request);
                break;
            case 'yearly':
                $data = $this->getStatsYearly($request);
                break;
            default:
                $data = [];
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    private function getStatsByMonth(Request $request)
    {
        $payments = MemberPayment::whereYear('valid_from', $request->year)->whereMonth('valid_from', $request->month)->get();
        $payments->load('member');

        return $payments;
    }

    private function getStatsMonthly(Request $request)
    {
        $data = [];

        $data = MemberPayment::select(
            DB::raw("SUM(value) AS valuesum"),
            DB::raw("DATE_FORMAT(valid_from, '%m') AS onlymonth"),
            DB::raw("DATE_FORMAT(valid_from, '%Y') AS onlyyear"),
            'currency_id'
        )
        ->orderBy('valid_from', 'desc')
        ->groupBy(DB::raw("onlymonth, onlyyear, currency_id"))
        ->get();

        return $data;
    }

    private function getStatsYearly(Request $request)
    {
        $data = [];

        $data = MemberPayment::select(
            DB::raw("SUM(value) AS valuesum"),
            DB::raw("DATE_FORMAT(valid_from, '%Y') AS onlyyear"),
            'currency_id'
        )
        ->orderBy('valid_from', 'desc')
        ->groupBy(DB::raw("onlyyear, currency_id"))
        ->get();

        return $data;
    }
}
