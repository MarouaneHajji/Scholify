<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    // The dashboard method for the student
    public function dashboard()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Get the associated student record
        $student = Student::where('user_id', $user->id)->first();

        return view('dashboard.student', compact('student'));
    }
}
