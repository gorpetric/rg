<?php

namespace App\Models\Members\Vacuum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Members\Vacuum\VacuumAppointmentMeasure;

class VacuumMeasureBodyPart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'part', 'unit',
    ];

    //protected $with = ['VacuumAppointmentMeasures'];

    public function VacuumAppointmentMeasures()
    {
        return $this->hasMany(VacuumAppointmentMeasure::class);
    }
}
