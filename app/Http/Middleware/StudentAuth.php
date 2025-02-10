<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->student()->exists()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
