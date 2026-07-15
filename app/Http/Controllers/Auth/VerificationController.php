<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend', 'resendUnauth');
    }

    /**
     * Show the email verification notice.
     */
    public function show(Request $request)
    {
        return view('auth.verify_sent');
    }

    /**
     * Mark the authenticated/unauthenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return view('auth.verify_failed');
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return view('auth.verify_failed');
        }

        if (!$request->hasValidSignature()) {
            return view('auth.verify_expired');
        }

        if ($user->hasVerifiedEmail()) {
            return view('auth.verify_success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('auth.verify_success');
    }

    /**
     * Resend verification email for unauthenticated or authenticated user by email.
     */
    public function resendUnauth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('verification.notice')
            ->with('resent', true)
            ->with('email', $request->input('email'));
    }
}
