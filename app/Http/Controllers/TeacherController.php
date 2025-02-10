<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Module;

class TeacherController extends Controller
{
    // Show the form to add a new teacher
    public function create()
    {
        return view('admin-section.teachers.create');
    }

    // Save a new teacher
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',

        ]);

        $profilePicturePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create the user
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        // Create the teacher
        Teacher::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
    }

    // List all teachers
    public function index()
    {
        $teachers = Teacher::with(['user', 'classes', 'modules'])->paginate(10);
        return view('admin-section.teachers.index', compact('teachers'));
    }

    // Show the details of a specific teacher
    public function show(Teacher $teacher)
    {
        $teacher->load(['classes', 'user']);

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

        // Get teacher's schedule slots with proper relationships
        $scheduleSlots = \App\Models\ScheduleSlot::with(['schedule.class', 'module'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('day');

        return view('admin-section.teachers.show', compact('teacher', 'timeSlots', 'scheduleSlots'));
    }

    // Show the form to edit a teacher
    public function edit(Teacher $teacher)
    {
        return view('admin-section.teachers.edit', compact('teacher'));
    }

    // Update a teacher
    public function update(Request $request, Teacher $teacher)
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',

        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete the old picture if it exists
            if ($teacher->profile_picture && file_exists(storage_path('app/public/' . $teacher->profile_picture))) {
                unlink(storage_path('app/public/' . $teacher->profile_picture));
            }
        
            // Store the new picture
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $teacher->update(['profile_picture' => $profilePicturePath]);
        }


        // Update the user
        $teacher->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $teacher->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update the teacher
        $teacher->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully!');
    }

    // Delete a teacher
    public function destroy(Teacher $teacher)
    {
        // Delete the profile picture if it exists
        if ($teacher->profile_picture && file_exists(storage_path('app/public/' . $teacher->profile_picture))) {
            unlink(storage_path('app/public/' . $teacher->profile_picture));
        }

        // Delete the associated user
        $teacher->user()->delete();

        // Delete the teacher
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully!');
    }


    public function classesOverview()
    {
        $teacher = auth()->user()->teacher;
        
        // Get unique classes with student count
        $classes = $teacher->classes()
            ->withCount('students')
            ->get()
            ->unique('id')
            ->values(); // Reset array keys after unique

        return view('teacher-section.classes.classes', compact('classes'));
    }

    public function profile()
    {
        return view('teacher-section.profile.profile');
    }

    public function classes()
    {
        $teacher = auth()->user()->teacher;
        
        // Get classes with student count
        $classes = $teacher->classes()
            ->withCount('students')
            ->get()
            ->unique('id')
            ->values();
        
        return view('teacher-section.classes.classes', compact('classes'));
    }

    public function showClass($classId)
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->classes()->findOrFail($classId);
        
        // Get modules through the teacher's relationship with the class
        $modules = $teacher->modules()
            ->wherePivot('classe_id', $classId)
            ->get();
        
        // Get paginated students
        $students = $class->students()
            ->orderBy('first_name')
            ->paginate(10); // You can adjust the number per page
        
        return view('teacher-section.classes.show', compact('class', 'modules', 'students'));
    }
}