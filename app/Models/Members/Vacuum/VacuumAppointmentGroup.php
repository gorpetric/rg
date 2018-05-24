<?php

namespace App\Models\Members\Vacuum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumMember;
use App\Models\Members\Vacuum\VacuumAppointment;

class VacuumAppointmentGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'num_of_appointments', 'price_per_appointment',
    ];

    public function VacuumMember()
    {
        return $this->belongsTo(VacuumMember::class);
    }

    public function VacuumAppointments()
    {
        return $this->hasMany(VacuumAppointment::class)->orderBy('id', 'desc');
    }
}
