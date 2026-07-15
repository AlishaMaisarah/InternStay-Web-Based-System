<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // If not logged in or not admin -> go back to user home
        if (!$user || ($user->role ?? 'user') !== 'admin') {
            return redirect()->route('public.dashboard');
        }

        return $next($request);
    }
}

