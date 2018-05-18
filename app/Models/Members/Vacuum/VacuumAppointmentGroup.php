<?php

namespace App\Models\Members\Vacuum;

use App\Models\Members\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumAppointment;

class VacuumAppointmentGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'num_of_appointments', 'price_per_appointment',
    ];

    protected $with = ['VacuumAppointments'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function VacuumAppointments()
    {
        return $this->hasMany(VacuumAppointment::class)->orderBy('id', 'desc');
    }
}
