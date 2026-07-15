<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Internship;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // only logged in users
    }

    /**
     * Show favourites page with 2 separate sections:
     * - internships favourites
     * - rentals favourites
     */
    public function index()
    {
        $user = Auth::user();

        // Load favourites + their related models
        $favorites = $user->favorites()->with('favoritable')->latest()->get();

        $internshipFavorites = $favorites
            ->where('favoritable_type', Internship::class)
            ->values();

        $rentalFavorites = $favorites
            ->where('favoritable_type', Rental::class)
            ->values();

        return view('public.favorites.index', compact('internshipFavorites', 'rentalFavorites'));
    }

    /** Add internship to favourites */
    public function addInternship(Internship $internship)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'favoritable_id' => $internship->id,
            'favoritable_type' => Internship::class,
        ]);

        return back()->with('success', 'Internship added to favourites!');
    }

    /** Remove internship from favourites */
    public function removeInternship(Internship $internship)
    {
        Favorite::where('user_id', Auth::id())
            ->where('favoritable_id', $internship->id)
            ->where('favoritable_type', Internship::class)
            ->delete();

        return back()->with('success', 'Internship removed from favourites!');
    }

    /** Add rental to favourites */
    public function addRental(Rental $rental)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'favoritable_id' => $rental->id,
            'favoritable_type' => Rental::class,
        ]);

        return back()->with('success', 'Rental added to favourites!');
    }

    /** Remove rental from favourites */
    public function removeRental(Rental $rental)
    {
        Favorite::where('user_id', Auth::id())
            ->where('favoritable_id', $rental->id)
            ->where('favoritable_type', Rental::class)
            ->delete();

        return back()->with('success', 'Rental removed from favourites!');
    }
}

