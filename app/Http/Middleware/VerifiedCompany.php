<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedCompany
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isCompany()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('company.dashboard')
                ->with('warning', 'Please verify your company email address to continue.');
        }

        if (!$user->isApprovedCompany()) {
            return redirect()->route('company.dashboard')
                ->with('warning', 'Your company account is still pending administrator approval.');
        }

        return $next($request);
    }
}
