<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        // Check if a user with this google_id already exists
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            // Update avatar if changed
            if ($user->avatar !== $googleUser->avatar) {
                $user->update(['avatar' => $googleUser->avatar]);
            }
        } else {
            // Check if user already exists with the same email address
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link the Google account to the existing email account
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // Create a new user account (role = user/student)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'user', // Default student user role
                ]);
                
                // Automatically verify email since it is verified by Google
                $user->email_verified_at = now();
                $user->save();
            }
        }

        // Log the user in
        Auth::login($user, true);

        // Redirect based on onboarding state
        if (!$user->onboarding_completed) {
            return redirect()->route('onboarding.welcome');
        }

        // Standard role redirect (same logic as LoginController)
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'company') {
            return redirect()->route('company.dashboard');
        }

        return redirect()->route('public.dashboard');
    }
}
