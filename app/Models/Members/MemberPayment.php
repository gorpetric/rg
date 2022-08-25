<?php

namespace App\Models\Members;

use App\Models\Currency;
use App\Models\Members\Member;
use Illuminate\Database\Eloquent\Model;

class MemberPayment extends Model
{
    protected $fillable = ['value', 'description', 'valid_from', 'valid_until', 'currency_id'];

    protected $dates = ['valid_from', 'valid_until'];

    protected $with = ['currency'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
