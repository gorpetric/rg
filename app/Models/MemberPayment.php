<?php

namespace App\Models;

use App\Models\Member;
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
