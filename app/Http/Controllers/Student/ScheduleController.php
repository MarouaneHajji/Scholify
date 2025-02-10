<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;
use PDF;

class ScheduleController extends Controller
{
    public function show(Classes $class)
    {
        // Ensure the authenticated student belongs to this class
        if (!auth()->user()->student->classes->contains($class)) {
            abort(403);
        }

        $schedule = Schedule::where('class_id', $class->id)
            ->with(['slots' => function($query) {
                $query->with(['teacher', 'module']);
            }])
            ->first();

        $data = [
            'class_name' => $class->name,
            'has_schedule' => !is_null($schedule),
            'schedule_raw' => $schedule,
            'schedule_decoded' => null
        ];

        if ($schedule) {
            $slotsByDay = [];
            foreach ($schedule->slots as $slot) {
                $slotsByDay[$slot->day][] = [
                    'start_time' => date('H:i', strtotime($slot->start_time)),
                    'end_time' => date('H:i', strtotime($slot->end_time)),
                    'teacher_name' => $slot->teacher->first_name . ' ' . $slot->teacher->last_name,
                    'module_name' => $slot->module->name,
                    'room' => $slot->room
                ];
            }
            $data['schedule_decoded'] = $slotsByDay;
        }

        return view('student-section.schedule.schedule', $data);
    }

    public function download(Classes $class)
    {
        // Ensure the authenticated student belongs to this class
        if (!auth()->user()->student->classes->contains($class)) {
            abort(403);
        }

        $schedule = Schedule::where('class_id', $class->id)
            ->with(['slots' => function($query) {
                $query->with(['teacher', 'module']);
            }])
            ->first();

        $data = [
            'class_name' => $class->name,
            'schedule_decoded' => []
        ];

        if ($schedule) {
            $slotsByDay = [];
            foreach ($schedule->slots as $slot) {
                $slotsByDay[$slot->day][] = [
                    'start_time' => date('H:i', strtotime($slot->start_time)),
                    'end_time' => date('H:i', strtotime($slot->end_time)),
                    'teacher_name' => $slot->teacher->first_name . ' ' . $slot->teacher->last_name,
                    'module_name' => $slot->module->name,
                    'room' => $slot->room
                ];
            }
            $data['schedule_decoded'] = $slotsByDay;
        }

        $pdf = PDF::loadView('student-section.schedule.schedule-pdf', $data);
        
        return $pdf->download($class->name . '_schedule.pdf');
    }
} 