<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        // Get all grades with their controls and modules
        $grades = Grade::with(['control.module'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('control.module.name');

        return view('student-section.grades.index', compact('grades', 'student'));
    }
} 