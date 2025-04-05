<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class StudentProfileController extends Controller
{
    public function show()
    {
        $student = auth()->user()->student;
        return view('student.profile', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth()->user()->student;
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($student->profile_picture) {
                Storage::delete($student->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $student->profile_picture = $path;
        }

        // Update student information
        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
        ]);

        // Update user email
        $student->user->update([
            'email' => $validated['email'],
        ]);

        // Handle password change if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $student->user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }

            $student->user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }
} 