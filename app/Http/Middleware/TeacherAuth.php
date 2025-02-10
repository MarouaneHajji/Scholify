<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Unauthorized access. Please login as a teacher.');
    }
}
