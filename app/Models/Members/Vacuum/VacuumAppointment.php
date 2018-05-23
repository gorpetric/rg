<?php

namespace App\Models\Members\Vacuum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumAppointmentGroup;
use App\Models\Members\Vacuum\VacuumAppointmentMeasure;

class VacuumAppointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_at', 'finished',
    ];

    //protected $with = ['VacuumAppointmentMeasures'];

    protected $dates = ['appointment_at'];

    public function VacuumAppointmentGroup()
    {
        return $this->belongsTo(VacuumAppointmentGroup::class);
    }

    public function VacuumAppointmentMeasures()
    {
        return $this->hasMany(VacuumAppointmentMeasure::class);
    }

    public function scopeFinished($query)
    {
        return $query->where('finished', 1);
    }

    public function scopeNonfinished($query)
    {
        return $query->where('finished', 0);
    }
}
