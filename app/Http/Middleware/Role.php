<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        foreach($roles as $role) {
            // Assuming user has a role attribute or method
            if($request->user()->role === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
} 