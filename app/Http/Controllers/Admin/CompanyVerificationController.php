<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // Get all company profiles, showing Pending first
        $profiles = CompanyProfile::with('user')
            ->orderByRaw("FIELD(verification_status, 'Pending', 'Approved', 'Rejected')")
            ->latest()
            ->get();

        return view('admin.verifications.index', compact('profiles'));
    }

    public function approve(CompanyProfile $profile)
    {
        $profile->update([
            'verification_status' => 'Approved',
            'rejection_reason' => null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Company '{$profile->company_name}' has been successfully approved.");
    }

    public function reject(Request $request, CompanyProfile $profile)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $profile->update([
            'verification_status' => 'Rejected',
            'rejection_reason' => $request->input('rejection_reason'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Company '{$profile->company_name}' verification has been rejected.");
    }
}
