<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function studentDashboard()
    {
        $student = auth()->user()->student->load(['classes.teachers.modules', 'classes.schedule']);
        return view('dashboard.student', compact('student'));
    }

    public function teacherDashboard()
    {
        $teacher = auth()->user()->teacher->load(['classes.students', 'classes.schedule', 'modules']);
        return view('dashboard.teacher', compact('teacher'));
    }
} 