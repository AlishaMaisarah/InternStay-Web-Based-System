<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.company_register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/'],
        ], [
            'password.regex' => 'The password must contain a mix of letters and numbers, and at least one special symbol.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!app()->environment('local')) {
                $email = $request->input('email');
                if ($email) {
                    $domain = strtolower(substr(strrchr($email, "@"), 1));
                    $freeDomains = [
                        'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 
                        'live.com', 'icloud.com', 'aol.com', 'zoho.com', 
                        'mail.com', 'yandex.com', 'protonmail.com', 'proton.me'
                    ];
                    if (in_array($domain, $freeDomains)) {
                        $validator->errors()->add('email', 'Please register using a valid work or company email address (personal email providers are not allowed).');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $documentPath = $file->store('company_documents', 'public');
        }

        // Create User
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'company',
            'onboarding_completed' => true, // Bypass standard student onboarding
        ]);

        // Create Company Profile
        CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->input('company_name'),
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
            'document_path' => $documentPath,
            'verification_status' => 'Pending',
        ]);

        // Send Email Verification Notification
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('email', $user->email)
            ->with('success', 'Company registration submitted successfully! Please check your company email to verify your account.');
    }
}
