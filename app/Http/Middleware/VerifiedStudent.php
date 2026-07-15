<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('student.login');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('public.dashboard')
                ->with('warning', 'Please verify your email address to access student features.');
        }

        return $next($request);
    }
}
