<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classe;
use App\Models\Module;

class AdminController extends Controller
{
   
    public function index()
    {
        // Fetch all admins (users with role = 'admin')
        $admins = User::where('role', 'admin')->get();
    
        // Return the admin management view
        return view('admin-section.admin-management.index', compact('admins'));
    }


public function create()
{
    return view('admin-section.admin-management.create');
}

public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
    ]);

    // Handle the profile picture upload
    $profilePicturePath = null;
    if ($request->hasFile('profile_picture')) {
        try {
            // Store directly in profile_pictures directory
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            // Verify the file was stored successfully
            if (!$profilePicturePath) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to upload profile picture. Please try again.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error uploading profile picture: ' . $e->getMessage());
        }
    }

    try {
        // Create the new admin
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'profile_picture' => $profilePicturePath,
        ]);

        // Verify the admin was created with the profile picture
        if (!$admin || !$admin->profile_picture) {
            // If something went wrong, delete the uploaded file
            if ($profilePicturePath) {
                \Storage::disk('public')->delete($profilePicturePath);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create admin with profile picture.');
        }

        return redirect()->route('admin.management')
            ->with('success', 'Admin created successfully!');
    } catch (\Exception $e) {
        // If something went wrong, delete the uploaded file
        if ($profilePicturePath) {
            \Storage::disk('public')->delete($profilePicturePath);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating admin: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    // Find the admin by ID
    $admin = User::findOrFail($id);

    // Validate the form input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $admin->id,
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
    ]);

    // Handle the profile picture upload if provided
    if ($request->hasFile('profile_picture')) {
        if ($admin->profile_picture) {
            // Delete the old picture
            \Storage::disk('public')->delete($admin->profile_picture);
        }
        // Store directly in profile_pictures directory
        $admin->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
    }
    // Update the admin details
    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'profile_picture' => $admin->profile_picture ?? null,
    ]);

    return redirect()->route('admin.management')->with('success', 'Admin updated successfully.');
}


public function edit($id)
{
    // Find the admin by ID
    $admin = User::findOrFail($id);

    // Pass the admin to the edit view
    return view('admin-section.admin-management.edit', compact('admin'));
}

public function destroy($id)
{
    try {
        // Find the admin by ID
        $admin = User::findOrFail($id);

        // Check if the user is an admin
        if ($admin->role !== 'admin') {
            return redirect()->route('admin.management')->with('error', 'Cannot delete this user.');
        }

        // Delete the profile picture if it exists
        if ($admin->profile_picture) {
            \Storage::disk('public')->delete($admin->profile_picture);
        }

        // Delete the admin
        $admin->delete();

        return redirect()->route('admin.management')->with('success', 'Admin deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('admin.management')->with('error', 'Error deleting admin: ' . $e->getMessage());
    }
}

public function dashboard()
{
    $stats = [
        'teachers' => Teacher::count(),
        'students' => Student::count(),
        'modules' => Module::count(),
        'classes' => Classe::count(),
    ];
    
    // If user is super admin, also get admin count
    if (auth()->user()->isSuperAdmin()) {
        $stats['admins'] = User::where('role', 'admin')->count();
    }

    return view('admin-section.dashboard.admin', compact('stats'));
}

}
