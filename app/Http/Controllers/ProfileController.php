<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Different validation rules based on user role
        if ($user->role === 'teacher') {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'phone' => ['required', 'string', 'max:20'],
                'current_password' => ['required_with:new_password', 'current_password'],
                'new_password' => ['nullable', 'confirmed', Password::defaults()],
            ]);

            // Update teacher profile
            $user->teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone_number' => $validated['phone'], // Changed 'phone' to 'phone_number' to match model
            ]);
        } else {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'current_password' => ['required_with:new_password', 'current_password'],
                'new_password' => ['nullable', 'confirmed', Password::defaults()],
            ]);

            $user->name = $validated['name'];
        }

        // Update common fields
        $user->email = $validated['email'];
        
        if (isset($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }
        
        $user->save();
        
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'] // 2MB max
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No profile picture provided'
        ], 400);
    }
}