@extends('layouts.template')

@section('content')

<div class="text-center mb-5">
    <h1 class="fw-bold">InternStay</h1>
    <p class="text-muted">
        Helping students discover internships and nearby accommodation with ease.
    </p>
</div>

<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="fw-bold mb-3">About Us</h4>
                <p>
                    InternStay is a platform designed to help students find suitable
                    internship opportunities and rental accommodations near their workplace.
                </p>
                <p>
                    We aim to simplify the search process by providing organised listings,
                    clear information, and easy navigation for students.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ route('public.internships.index') }}" class="btn btn-primary me-2">
        Browse Internships
    </a>

    <a href="{{ route('public.rentals.index') }}" class="btn btn-outline-primary">
        Browse Rentals
    </a>
</div>

@endsection
