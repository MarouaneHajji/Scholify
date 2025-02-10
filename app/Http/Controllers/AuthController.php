<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'admin' || $user->role === 'super_admin') {
                return redirect()->route('admin.dashboard'); // Changed from dashboard.admin
            } elseif ($user->role === 'teacher') {
                return redirect()->route('dashboard.teacher');
            } elseif ($user->role === 'student') {
                return redirect()->route('dashboard.student');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    

    public function logout()
    {
        session()->forget('user_id');
        return redirect('/login');
    }

}
