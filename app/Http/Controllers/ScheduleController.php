<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Models\Classe;
use App\Models\Teacher;
use App\Models\Module;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['class'])->get();
        $classes = Classe::whereNotIn('id', function($query) {
            $query->select('class_id')->from('schedules');
        })->get();
        return view('admin-section.schedules.index', compact('schedules', 'classes'));
    }

    public function create()
    {
        $availableClasses = Classe::whereNotIn('id', function($query) {
            $query->select('class_id')->from('schedules');
        })->get();
        
        // Initialize empty teacherClassSubjects array
        $teacherClassSubjects = [];
        
        return view('admin-section.schedules.create', compact('availableClasses', 'teacherClassSubjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => [
                'required',
                'exists:classes,id',
                'unique:schedules,class_id'
            ],
            'schedule' => 'required|array'
        ], [
            'class_id.unique' => 'This class already has a schedule assigned.'
        ]);

        // Check for teacher scheduling conflicts
        $conflicts = $this->checkTeacherConflicts($request->schedule);
        if ($conflicts) {
            return back()
                ->withInput()
                ->withErrors(['schedule' => $conflicts]);
        }

        $schedule = Schedule::create([
            'class_id' => $request->class_id
        ]);

        // Flatten the schedule array and create slots
        foreach ($request->schedule as $day => $slots) {
            foreach ($slots as $slot) {
                ScheduleSlot::create([
                    'schedule_id' => $schedule->id,
                    'day' => $day,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'teacher_id' => $slot['teacher_id'],
                    'module_id' => $slot['module_id'],
                    'room' => $slot['room']
                ]);
            }
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    public function show(Schedule $schedule)
    {
        $slotsByDay = [];
        $slots = $schedule->slots()
            ->with(['teacher', 'module'])
            ->get();

        $slots->each(function ($slot) use (&$slotsByDay) {
            // Ensure consistent time format
            $startTime = date('H:i', strtotime($slot->start_time));
            $endTime = date('H:i', strtotime($slot->end_time));
            
            $slotsByDay[$slot->day][$startTime] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'teacher_name' => $slot->teacher->first_name . ' ' . $slot->teacher->last_name,
                'module_name' => $slot->module->name,
                'room' => $slot->room
            ];
        });

        $timeSlots = [
            '08:00' => '8h à 9h',
            '09:00' => '9h à 10h',
            '10:00' => '10h à 11h',
            '11:00' => '11h à 12h',
            '12:00' => '12h à 13h',
            '13:00' => '13h à 14h',
            '14:00' => '14h à 15h',
            '15:00' => '15h à 16h',
            '16:00' => '16h à 17h',
            '17:00' => '17h à 18h'
        ];

        return view('admin-section.schedules.show', compact('schedule', 'slotsByDay', 'timeSlots'));
    }

    public function edit(Schedule $schedule)
    {
        $schedule->load(['class', 'slots.teacher', 'slots.module']);
        
        // Get teacher-class-subjects mapping for the class
        $teacherClassSubjects = [];
        $teacherClassSubjects[$schedule->class_id] = $schedule->class->teachers()
            ->with('modules')
            ->get()
            ->map(function ($teacher) {
                $module = Module::find($teacher->pivot->module_id);
                return [
                    'teacher_id' => $teacher->id,
                    'module_id' => $teacher->pivot->module_id,
                    'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'module_name' => $module ? $module->name : 'Unknown Module'
                ];
            });

        // Prepare schedule data with existing slots
        $scheduleData = [];
        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
            $scheduleData[$day] = $schedule->slots()
                ->where('day', $day)
                ->get()
                ->map(function($slot) {
                    return [
                        'start_time' => substr($slot->start_time, 0, 5),
                        'end_time' => substr($slot->end_time, 0, 5),
                        'teacher_id' => (string)$slot->teacher_id,
                        'module_id' => (string)$slot->module_id,
                        'room' => $slot->room
                    ];
                })
                ->values()
                ->toArray();
        }

        return view('admin-section.schedules.edit', compact(
            'schedule',
            'teacherClassSubjects',
            'scheduleData'
        ));
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'schedule' => 'required|array'
        ]);

        // Check for teacher scheduling conflicts, excluding current schedule
        $conflicts = $this->checkTeacherConflicts($request->schedule, $id);
        if ($conflicts) {
            return back()
                ->withInput()
                ->withErrors(['schedule' => $conflicts]);
        }

        $schedule->update([
            'class_id' => $request->class_id
        ]);

        // Delete existing slots
        $schedule->slots()->delete();

        // Create new slots
        foreach ($request->schedule as $day => $slots) {
            foreach ($slots as $slot) {
                ScheduleSlot::create([
                    'schedule_id' => $schedule->id,
                    'day' => $day,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'teacher_id' => $slot['teacher_id'],
                    'module_id' => $slot['module_id'],
                    'room' => $slot['room']
                ]);
            }
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->slots()->delete();
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    public function downloadPdf(Schedule $schedule)
    {
        $schedule->load(['class', 'slots.teacher', 'slots.module']);
        
        // Get slots grouped by day
        $slotsByDay = [];
        foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
            $daySlots = $schedule->slots->where('day', $day)->map(function($slot) {
                return [
                    'start_time' => sprintf('%02d:00', intval(substr($slot->start_time, 0, 2))),
                    'end_time' => sprintf('%02d:00', intval(substr($slot->end_time, 0, 2))),
                    'teacher_name' => $slot->teacher->first_name . ' ' . $slot->teacher->last_name,
                    'module_name' => $slot->module->name,
                    'subject_name' => $slot->module->name,
                    'room' => $slot->room
                ];
            })->keyBy('start_time')->toArray();
            
            $slotsByDay[$day] = $daySlots;
        }

        $timeSlots = [
            '08:00' => '8h à 9h',
            '09:00' => '9h à 10h',
            '10:00' => '10h à 11h',
            '11:00' => '11h à 12h',
            '12:00' => '12h à 13h',
            '13:00' => '13h à 14h',
            '14:00' => '14h à 15h',
            '15:00' => '15h à 16h',
            '16:00' => '16h à 17h',
            '17:00' => '17h à 18h'
        ];

        $pdf = PDF::loadView('admin-section.schedules.pdf', compact('schedule', 'slotsByDay', 'timeSlots'));
        
        return $pdf->download($schedule->class->name . '_schedule.pdf');
    }

    // Add a new method to fetch teacher-class-subjects
    public function getTeacherClassSubjects(Classe $class)
    {
        $teacherClassSubjects = $class->teachers()
            ->with('modules')
            ->get()
            ->map(function ($teacher) {
                $module = Module::find($teacher->pivot->module_id);
                return [
                    'teacher_id' => $teacher->id,
                    'module_id' => $teacher->pivot->module_id,
                    'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'module_name' => $module ? $module->name : 'Unknown Module'
                ];
            });
        
        return response()->json($teacherClassSubjects);
    }

    /**
     * Check for teacher scheduling conflicts
     * 
     * @param array $scheduleData
     * @param int|null $excludeScheduleId
     * @return string|null
     */
    private function checkTeacherConflicts($scheduleData, $excludeScheduleId = null)
    {
        foreach ($scheduleData as $day => $slots) {
            foreach ($slots as $newSlot) {
                $teacherId = $newSlot['teacher_id'];
                $startTime = $newSlot['start_time'];
                $endTime = $newSlot['end_time'];

                // Query to find any existing slots that overlap with the new slot
                $query = ScheduleSlot::where('day', $day)
                    ->where('teacher_id', $teacherId)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->where(function ($q) use ($startTime, $endTime) {
                            // Check if the new slot overlaps with existing slots
                            $q->where('start_time', '<', $endTime)
                                ->where('end_time', '>', $startTime);
                        });
                    });

                // Exclude current schedule if updating
                if ($excludeScheduleId) {
                    $query->whereNotIn('schedule_id', [$excludeScheduleId]);
                }

                $conflictingSlot = $query->first();

                if ($conflictingSlot) {
                    $teacher = Teacher::find($teacherId);
                    $teacherName = $teacher->first_name . ' ' . $teacher->last_name;
                    return "Scheduling conflict: {$teacherName} is already scheduled on {$day} from " . 
                           substr($conflictingSlot->start_time, 0, 5) . " to " . 
                           substr($conflictingSlot->end_time, 0, 5);
                }
            }
        }

        return null;
    }
}
