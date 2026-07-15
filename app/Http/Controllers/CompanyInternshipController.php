<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyInternshipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified.company']);
    }

    public function create()
    {
        return view('company.internships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'internship_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $companyName = Auth::user()->companyProfile->company_name;

        Internship::create([
            'user_id' => Auth::id(),
            'internship_name' => $request->input('internship_name'),
            'company' => $companyName,
            'industry' => $request->input('industry'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'contact_person' => $request->input('contact_person'),
            'contact_email' => $request->input('contact_email'),
            'contact_phone' => $request->input('contact_phone'),
            'source' => 'Company Portal',
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'is_closed' => false,
            'is_suspended' => false,
        ]);

        return redirect()->route('company.dashboard')->with('success', 'Internship listing posted successfully!');
    }

    public function edit(Internship $internship)
    {
        // Authorize company ownership
        if ($internship->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('company.internships.edit', compact('internship'));
    }

    public function update(Request $request, Internship $internship)
    {
        // Authorize company ownership
        if ($internship->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'internship_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $internship->update([
            'internship_name' => $request->input('internship_name'),
            'industry' => $request->input('industry'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'contact_person' => $request->input('contact_person'),
            'contact_email' => $request->input('contact_email'),
            'contact_phone' => $request->input('contact_phone'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
        ]);

        return redirect()->route('company.dashboard')->with('success', 'Internship listing updated successfully!');
    }

    public function destroy(Internship $internship)
    {
        // Authorize company ownership
        if ($internship->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $internship->delete();

        return redirect()->route('company.dashboard')->with('success', 'Internship listing deleted successfully!');
    }
}
