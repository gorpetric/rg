<?php

namespace App\Models\Members\Vacuum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumAppointment;
use App\Models\Members\Vacuum\VacuumMeasureBodyPart;

class VacuumAppointmentMeasure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vacuum_measure_body_part_id', 'measure', 'before_or_after',
    ];

    public function VacuumAppointment()
    {
        return $this->belongsTo(VacuumAppointment::class);
    }

    public function VacuumMeasureBodyPart()
    {
        return $this->belongsTo(VacuumMeasureBodyPart::class);
    }
}
