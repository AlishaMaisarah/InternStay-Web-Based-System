<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Internship;
use App\Models\Rental;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $internships = Internship::all();
        $recentInternships = Internship::latest()->take(5)->get();
        $rentals = Rental::all();
        return view('home', compact('internships', 'recentInternships', 'rentals'));
    }
}
