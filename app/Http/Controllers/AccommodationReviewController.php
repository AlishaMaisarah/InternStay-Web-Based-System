<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\AccommodationReview;
use Illuminate\Http\Request;

class AccommodationReviewController extends Controller
{
    public function store(Request $request, Rental $rental)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        AccommodationReview::create([
            'accommodation_id' => $rental->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
}
