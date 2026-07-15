<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = '/onboarding';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/'],
        ], [
            'password.regex' => 'The password must contain a mix of letters and numbers, and at least one special symbol.',
        ]);
    }

    /**
     * Create a normal USER account (role=user).
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // IMPORTANT: default user
        ]);
    }

    /**
     * This runs AFTER the user is registered AND logged in by default.
     * Redirect to onboarding for new users.
     */
    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();
        Auth::logout();

        return redirect()->route('verification.notice')
            ->with('email', $user->email)
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}

