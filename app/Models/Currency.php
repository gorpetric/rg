<?php

namespace App\Models;

use App\Models\Members\MemberPayment;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'symbol', 'code'];

    public function memberPayments()
    {
        return $this->hasMany(MemberPayment::class);
    }
}
