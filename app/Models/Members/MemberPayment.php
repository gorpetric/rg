<?php

namespace App\Models\Members;

use App\Models\Members\Member;
use Illuminate\Database\Eloquent\Model;

class MemberPayment extends Model
{
    protected $fillable = ['value', 'description', 'valid_from', 'valid_until'];

    protected $dates = ['valid_from', 'valid_until'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
