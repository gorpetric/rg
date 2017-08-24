<?php

namespace App\Models;

use App\Models\MemberPayment;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'sex', 'active', 'joined_at'];

    protected $dates = ['joined_at'];

    protected $with = ['payments'];

    public function payments()
    {
        return $this->hasMany(MemberPayment::class)->orderBy('valid_until', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }
}
