<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScheduleSlot;

class TeacherDashboardController extends Controller
{
    // Show the teacher dashboard
    public function dashboard()
    {
        $teacher = auth()->user()->teacher;
        
        // Get schedule slots for today
        $scheduleSlots = \App\Models\ScheduleSlot::with(['schedule.class', 'module'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('day');

        // Calculate stats
        $stats = [
            'classes' => $teacher->classes()->count(),
            'students' => $teacher->classes()->withCount('students')->get()->sum('students_count'),
            'modules' => $teacher->modules()->count(),
            'assignments' => 0 // You can implement this later
        ];

        // Get classes with student count
        $classes = $teacher->classes()
            ->withCount('students')
            ->get()
            ->unique('id')
            ->values();

        // Get modules
        $modules = $teacher->modules;

        // Define time slots
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

        return view('teacher-section.dashboard.teacher', compact(
            'scheduleSlots',
            'stats',
            'classes',
            'modules',
            'timeSlots',
            'teacher'
        ));
    }
}