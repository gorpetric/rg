<?php

namespace App\Models\Members\Vacuum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumAppointmentGroup;

class VacuumMember extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'address', 'phone', 'sex'];

    public function VacuumAppointmentGroups()
    {
        return $this->hasMany(VacuumAppointmentGroup::class)->orderBy('id', 'desc');
    }
}
