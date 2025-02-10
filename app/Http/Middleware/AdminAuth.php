<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Use auth()->user() to get the authenticated user
        $user = auth()->user();

        // Check if the user exists and is authorized (super_admin or admin)
        if (!$user || ($user->role !== 'admin' && $user->role !== 'super_admin')) {
            return redirect('/login'); // Redirect unauthorized users to login
        }

        return $next($request); // Allow the request to continue
    }
}