<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classe;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::with(['user', 'classes'])->paginate(10);
        $classes = Classe::all();
        return view('admin-section.students.index', compact('students', 'classes'));
    }


    public function create()
    {
        return view('admin-section.students.create');
    }


    public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'phone_number' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle the profile picture upload
    $profilePicturePath = null;
    if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
    }

    // Create the user
    $user = \App\Models\User::create([
        'name' => $request->first_name . ' ' . $request->last_name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'role' => 'student',
    ]);

    // Create the student
    \App\Models\Student::create([
        'user_id' => $user->id,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'phone_number' => $request->phone_number,
        'date_of_birth' => $request->date_of_birth,
        'address' => $request->address,
        'profile_picture' => $profilePicturePath,
    ]);

    return redirect()->route('students.index')->with('success', 'New student added!');
}

public function edit(\App\Models\Student $student)
{
    return view('admin-section.students.edit', compact('student'));
}

public function update(Request $request, \App\Models\Student $student)
{
    // Validate the input
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $student->user_id,
        'phone_number' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle the profile picture upload
    if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Delete the old profile picture if it exists
        if ($student->profile_picture) {
            \Storage::disk('public')->delete($student->profile_picture);
        }

        // Update the student's profile picture path
        $student->profile_picture = $profilePicturePath;
    }

    // Update the user associated with the student
    $student->user->update([
        'name' => $request->first_name . ' ' . $request->last_name,
        'email' => $request->email,
    ]);

    // Update the student's other details
    $student->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'phone_number' => $request->phone_number,
        'date_of_birth' => $request->date_of_birth,
        'address' => $request->address,
    ]);

    return redirect()->route('students.index')->with('success', 'Student updated!');
}


public function show(Student $student)
{
    $student->load(['classes', 'user']);
    return view('admin-section.students.show', compact('student'));
}


public function destroy(Student $student)
{
    // Delete the associated user record
    $student->user()->delete();

    // Delete the student record
    $student->delete();

    return redirect()->route('students.index')->with('success', 'Student deleted!');
}

public function enrollInClass(Request $request, Student $student)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id'
    ]);

    $student->classes()->sync([$request->class_id]);

    return back()->with('success', 'Student enrolled successfully');
}

}
