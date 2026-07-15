<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipReview;
use Illuminate\Http\Request;

class InternshipReviewController extends Controller
{
    public function store(Request $request, Internship $internship)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        InternshipReview::create([
            'internship_id' => $internship->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
}
