<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is a company
        if (!$user->isCompany()) {
            abort(403, 'Unauthorized access.');
        }

        $profile = $user->companyProfile;
        
        if (!$profile) {
            abort(404, 'Company profile not found.');
        }

        // If approved, load postings and stats
        $internships = collect();
        $stats = [
            'total' => 0,
            'active' => 0,
            'suspended' => 0,
        ];

        if ($profile->verification_status === 'Approved') {
            $internships = $user->internships()->latest()->get();
            $stats['total'] = $internships->count();
            $stats['active'] = $internships->where('is_closed', false)->where('is_suspended', false)->count();
            $stats['suspended'] = $internships->where('is_suspended', true)->count();
        }

        return view('company.dashboard', compact('profile', 'internships', 'stats'));
    }
}
