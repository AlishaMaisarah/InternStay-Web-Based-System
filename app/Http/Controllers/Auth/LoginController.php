<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     * Enforce portal-specific role checks.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where($this->username(), $request->input($this->username()))->first();

        if ($user) {
            $loginRole = $request->input('login_role');

            if ($loginRole === 'student') {
                if ($user->role !== 'student' && $user->role !== 'user') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $this->username() => ['This account is registered as a Company PIC / Admin. Please log in through the appropriate portal.'],
                    ]);
                }
            } elseif ($loginRole === 'company') {
                if ($user->role !== 'company') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $this->username() => ['This account is registered as a Student. Please log in through the Student Portal.'],
                    ]);
                }
            } elseif ($loginRole === 'admin') {
                if ($user->role !== 'admin') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $this->username() => ['This account is registered as a Student / Company PIC. Please log in through the appropriate portal.'],
                    ]);
                }
            }
        }
    }

    /**
     * After login:
     * - admin -> /admin
     * - company -> /company/dashboard
     * - user  -> /
     */
    // Role-based redirect after login
    protected function authenticated(Request $request, $user)
    {
        if (($user->role ?? 'user') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Enforce Email Verification check for Students and Company PICs
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = ($user->role === 'company')
                ? 'Please verify your company email before logging in.'
                : 'Please verify your email address before logging in.';

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('unverified_email', $user->email)
                ->withErrors(['email' => $message]);
        }

        if (($user->role ?? 'user') === 'company') {
            return redirect()->route('company.dashboard');
        }

        return redirect()->route('public.dashboard');
    }

    /**
     * Logout -> go to /login
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the role selection page
     */
    public function showRoleSelection()
    {
        return view('auth.role_selection');
    }

    /**
     * Show student login form
     */
    public function showStudentLoginForm()
    {
        return view('auth.login', ['role' => 'student']);
    }

    /**
     * Show company login form
     */
    public function showCompanyLoginForm()
    {
        return view('auth.login', ['role' => 'company']);
    }

    /**
     * Show admin login form
     */
    public function showAdminLoginForm()
    {
        return view('auth.login', ['role' => 'admin']);
    }
}
