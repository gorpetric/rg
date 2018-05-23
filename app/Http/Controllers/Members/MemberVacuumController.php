<?php

namespace App\Http\Controllers\Members;

use Illuminate\Http\Request;
use App\Models\Members\Member;
use App\Http\Controllers\Controller;
use App\Models\Members\Vacuum\VacuumAppointment;
use App\Models\Members\Vacuum\VacuumMeasureBodyPart;
use App\Models\Members\Vacuum\VacuumAppointmentGroup;
use App\Models\Members\Vacuum\VacuumAppointmentMeasure;

class MemberVacuumController extends Controller
{
    public function index(Request $request, Member $member)
    {
        $appointments = VacuumAppointment::with('VacuumAppointmentGroup.member')->orderBy('appointment_at', 'asc')->get();
        $members = Member::orderBy('name', 'asc')->get();
        return view('members.vacuum.index', compact('appointments', 'members'));
    }

    public function member(Request $request, Member $member)
    {
        $member->load('VacuumAppointmentGroups.VacuumAppointments.VacuumAppointmentMeasures');
        $parts = VacuumMeasureBodyPart::get();

        return view('members.vacuum.member', compact('member', 'parts'));
    }

    public function createGroup(Request $request, Member $member)
    {
        $this->validate($request, [
            'num_of_appointments' => 'required|integer',
            'price_per_appointment' => 'required|integer',
        ]);

        $g = $member->VacuumAppointmentGroups()->create([
            'num_of_appointments' => $request->num_of_appointments,
            'price_per_appointment' => $request->price_per_appointment,
        ]);

        logdb('Vacuum group created', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $g->id,
        ]);

        $g->load('VacuumAppointments.VacuumAppointmentMeasures');

        return response()->json([
            'group' => $g,
        ]);
    }

    public function createAppointment(Request $request, Member $member, VacuumAppointmentGroup $group)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $appointment = $group->VacuumAppointments()->create([
            'appointment_at' => $request->date . $request->time . ':00',
            'finished' => 0,
        ]);

        logdb('Vacuum appointment created', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $group->id,
            'appointment' => $appointment->id,
        ]);

        $appointment->load('VacuumAppointmentMeasures');

        return response()->json([
            'appointment' => $appointment,
        ]);
    }

    public function createMeasurement(Request $request, Member $member, VacuumAppointmentGroup $group, VacuumAppointment $appointment)
    {
        $this->validate($request, [
            'part' => 'required|exists:vacuum_measure_body_parts,id',
            'before' => 'required|numeric',
            'after' => 'required|numeric',
        ]);

        $measureBefore = $appointment->VacuumAppointmentMeasures()->create([
            'vacuum_measure_body_part_id' => $request->part,
            'measure' => $request->before,
            'before_or_after' => 'B',
        ]);
        $measureAfter = $appointment->VacuumAppointmentMeasures()->create([
            'vacuum_measure_body_part_id' => $request->part,
            'measure' => $request->after,
            'before_or_after' => 'A',
        ]);

        logdb('Vacuum appointment measures created', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $group->id,
            'appointment' => $appointment->id,
            'before' => $measureBefore->id,
            'after' => $measureAfter->id,
        ]);

        return response()->json([
            'before' => $measureBefore,
            'after' => $measureAfter,
        ]);
    }

    public function deleteMeasurement(Request $request, Member $member, VacuumAppointmentGroup $group, VacuumAppointment $appointment)
    {
        $this->validate($request, [
            'ids.*' => 'exists:vacuum_appointment_measures,id'
        ]);

        foreach($request->ids as $id) {
            $m = VacuumAppointmentMeasure::find($id);
            $m->delete();
        }

        logdb('Vacuum appointment measures deleted', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $group->id,
            'appointment' => $appointment->id,
            'ids' => $request->ids,
        ]);

        return response(null, 200);
    }

    public function completeAppointment(Request $request, Member $member, VacuumAppointmentGroup $group, VacuumAppointment $appointment)
    {
        $this->validate($request, [
            'finished' => 'required|boolean'
        ]);

        $appointment->update([
            'finished' => $request->finished ? 1 : 0,
        ]);

        $logword = $request->finished ? 'completed' : 'completed (restored)';

        logdb('Vacuum appointment ' . $logword, [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $group->id,
            'appointment' => $appointment->id,
        ]);

        return response(null, 200);
    }

    public function editAppointment(Request $request, Member $member, VacuumAppointmentGroup $group, VacuumAppointment $appointment)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $appointment->update([
            'appointment_at' => $request->date . $request->time . ':00',
        ]);

        logdb('Vacuum appointment edited', [
            'by' => auth()->user()->id,
            'member' => $member->id,
            'group' => $group->id,
            'appointment' => $appointment->id,
        ]);

        return response(null, 200);
    }
}
