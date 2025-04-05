<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        return view('student.subjects.index', compact('student'));
    }

    public function show($id)
    {
        $student = auth()->user()->student;
        $module = Module::findOrFail($id);
        
        // Get the class that contains this module for this student
        $class = $student->classes()->whereHas('modules', function($query) use ($id) {
            $query->where('modules.id', $id);
        })->firstOrFail();

        return view('student.subjects.show', compact('module', 'class'));
    }
} 