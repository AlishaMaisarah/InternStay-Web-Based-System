<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->role === 'company') {
                return redirect()->route('company.dashboard')
                    ->with('warning', 'As a Company PIC, you are restricted to the Company Portal.');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'As an Admin, you are restricted to the Admin Console.');
            }
        }

        return $next($request);
    }
}
