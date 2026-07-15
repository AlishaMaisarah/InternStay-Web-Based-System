<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PublicDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->role === 'company') {
                return redirect()->route('company.dashboard');
            }
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        $featuredInternships = \App\Models\Internship::where('is_suspended', false)
            ->where('is_closed', false)
            ->orderByDesc('id')
            ->take(3)
            ->get();

        $featuredRentals = \App\Models\Rental::where('is_available', true)
            ->where('is_closed', false)
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('public.dashboard', compact('featuredInternships', 'featuredRentals'));
    }
}

