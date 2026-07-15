<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $internships = Internship::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('internship_name', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%")
                        ->orWhere('industry', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%")
                        ->orWhere('source', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('internships.index', compact('internships', 'q'));
    }

    public function create()
    {
        return view('internships.create');
    }

    public function store(Request $request)
    {
        $validIndustries = [
            'IT/Information Technology',
            'Engineering',
            'Business/Accounting/Finance',
            'Healthcare/Medical',
            'Build/Architecture/Construction',
            'Creative/Design',
            'Admin/Human Resource'
        ];

        $request->validate([
            'internship_name' => 'required|string|max:255',
            'company'         => 'required|string|max:255',
            'industry'        => ['required', 'string', 'in:' . implode(',', $validIndustries)],
            'location'        => 'required|string|max:255',
            'source_url'      => 'nullable|url|max:2048',
            /*'contact_email'   => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== '-' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Contact email must be a valid email or "-"');
                    }
                }
            ],*/

        ]);

        Internship::create([
            'internship_name' => $request->internship_name,
            'company'         => $request->company,
            'industry'        => $request->industry,
            'location'        => $request->location,
            'source_url'      => $request->source_url,
            //'contact_email'   => $request->contact_email ?? '-',
            //'contact_phone'   => $request->contact_phone,
            'description'     => $request->description,
            'is_closed'       => $request->has('is_closed'),
        ]);

        return redirect()->route('internships.index')
            ->with('success', 'Internship created successfully');
    }

    public function show(Internship $internship)
    {
        return view('internships.show', compact('internship'));
    }

    public function edit(Internship $internship)
    {
        return view('internships.edit', compact('internship'));
    }

    public function update(Request $request, Internship $internship)
    {
        $validIndustries = [
            'IT/Information Technology',
            'Engineering',
            'Business/Accounting/Finance',
            'Healthcare/Medical',
            'Build/Architecture/Construction',
            'Creative/Design',
            'Admin/Human Resource'
        ];

        $request->validate([
            'internship_name' => 'required|string|max:255',
            'company'         => 'required|string|max:255',
            'industry'        => ['required', 'string', 'in:' . implode(',', $validIndustries)],
            'location'        => 'required|string|max:255',
            'source_url'      => 'nullable|url|max:2048',
            /*'contact_email'   => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== '-' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Contact email must be a valid email or "-"');
                    }
                }
            ],*/

        ]);

        $internship->update([
            'internship_name' => $request->internship_name,
            'company'         => $request->company,
            'industry'        => $request->industry,
            'location'        => $request->location,
            'source_url'      => $request->source_url,
            //'contact_email'   => $request->contact_email ?? '-',
            //'contact_phone'   => $request->contact_phone,
            'description'     => $request->description,
            'is_closed'       => $request->has('is_closed'),
        ]);

        return redirect()->route('internships.index')
            ->with('success', 'Internship updated successfully');
    }

    public function destroy(Internship $internship)
    {
        $internship->delete();

        return redirect()->route('internships.index')
            ->with('success', 'Internship deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('internships.index')
                ->with('error', 'No internships selected for deletion');
        }

        $count = Internship::whereIn('id', $ids)->delete();

        return redirect()->route('internships.index')
            ->with('success', "{$count} internship(s) deleted successfully");
    }
}
