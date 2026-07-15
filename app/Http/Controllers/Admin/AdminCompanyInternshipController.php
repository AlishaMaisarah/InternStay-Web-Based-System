<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\Request;

class AdminCompanyInternshipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // Get all internships posted by company users (user_id is not null)
        $internships = Internship::whereNotNull('user_id')
            ->with('user.companyProfile')
            ->latest()
            ->get();

        return view('admin.company_internships.index', compact('internships'));
    }

    public function toggleSuspend(Internship $internship)
    {
        $internship->is_suspended = !$internship->is_suspended;
        $internship->save();

        $status = $internship->is_suspended ? 'suspended' : 'activated';

        return redirect()->route('admin.company-internships.index')
            ->with('success', "Internship listing '{$internship->internship_name}' has been successfully {$status}.");
    }

    public function destroy(Internship $internship)
    {
        $internship->delete();

        return redirect()->route('admin.company-internships.index')
            ->with('success', "Internship listing '{$internship->internship_name}' has been deleted.");
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.company-internships.index')
                ->with('error', 'No internship listings selected for deletion');
        }

        $count = Internship::whereIn('id', $ids)->delete();

        return redirect()->route('admin.company-internships.index')
            ->with('success', "{$count} company internship listing(s) deleted successfully");
    }
}
