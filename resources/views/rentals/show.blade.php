@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>Rental Accommodation Details</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $rental->property_name }}</h5>
            <p class="card-text"><strong>Type:</strong> {{ $rental->property_type }}</p>
            <p class="card-text"><strong>Address:</strong> {{ $rental->address }}</p>
            <p class="card-text"><strong>Rent Amount:</strong> {{ $rental->currency }} {{ number_format($rental->rent_amount, 2) }}</p>
            <p class="card-text"><strong>Bedrooms:</strong> {{ $rental->bedrooms ?? 'N/A' }}</p>
            <p class="card-text"><strong>Bathrooms:</strong> {{ $rental->bathrooms ?? 'N/A' }}</p>
            <p class="card-text"><strong>Description:</strong> {!! nl2br(e($rental->description ?? 'No description available' )) !!}</p>


            <p class="card-text"><strong>Status:</strong>
                @if($rental->is_available)
                    <span class="badge bg-success">Available</span>
                @else
                    <span class="badge bg-danger">Occupied</span>
                @endif
            </p>
        </div>
    </div>

    <a href="{{ route('rentals.index') }}" class="btn btn-secondary">Back to Rentals</a>
    <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-primary">Edit</a>
</div>
@endsection