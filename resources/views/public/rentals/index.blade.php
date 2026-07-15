@extends('layouts.template')

@section('content')

{{-- Page Header --}}
<div class="mb-4">
    <h2 class="fw-bold mb-2" style="color: #1f0822;">Explore Rental Accommodations</h2>
    <p class="text-muted">Find nearby accommodation suitable for interns</p>
</div>

{{-- Search & Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('public.rentals.index') }}">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0" 
                               placeholder="Search property, type, address..." 
                               value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <select name="property_type" class="form-select">
                        <option value="">All Property Types</option>
                        @foreach($property_types as $opt)
                            <option value="{{ $opt }}" {{ request('property_type') === $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <select name="state" class="form-select">
                        <option value="">All States</option>
                        @foreach($states as $opt)
                            <option value="{{ $opt }}" {{ request('state') === $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">RM</span>
                        <input type="number" name="max_price" class="form-control border-start-0" 
                               placeholder="Max Budget" 
                               value="{{ request('max_price') }}" min="0">
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 col-12">
                    <button class="btn btn-primary w-100" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
            @if(request('q') || request('state') || request('property_type') || request('max_price') || (request('lat') && request('lng')))
                <div class="mt-3">
                    <a href="{{ route('public.rentals.index') }}" class="btn btn-sm btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                        Clear filters ✕
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Location Button --}}
<div class="mb-4">
    <button id="nearMeBtn" class="btn btn-outline-primary" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
        </svg>
        Find rentals near me 📍
    </button>

    @if(request('lat') && request('lng'))
        <!--<a class="btn btn-outline-secondary ms-2" href="{{ route('public.rentals.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
            </svg>
            Clear location ✕
        </a>-->
        <div class="alert alert-info mt-2 py-2" style="border-left: 5px solid #6f42c1; background-color: rgba(243, 235, 252, 0.85); border-radius: 12px; border-top: none; border-right: none; border-bottom: none; color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6f42c1" class="me-1" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
            Showing rentals near your location 📍
        </div>
    @endif

    <form id="geoForm" method="GET" action="{{ route('public.rentals.index') }}" style="display:none;">
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">
    </form>
</div>

{{-- Recommended Rentals --}}
<!--@if(isset($recommendedRentals) && $recommendedRentals->isNotEmpty())
    <div class="mb-4">
        <h4 class="fw-bold mb-3" style="color: #198754;">
            🔥 Recommended for your Budget
        </h4>
        <div class="row g-4">
            @foreach($recommendedRentals as $rRental)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="border-top: 5px solid #198754 !important;">
                        @if($rRental->image_url && !str_contains($rRental->image_url, 'image-fallback') && !str_contains($rRental->image_url, 'placeholder'))
                            <div style="height: 120px; overflow: hidden; border-radius: 14px;">
                                <img src="{{ $rRental->image_url }}" class="card-img-top" alt="{{ $rRental->property_name }}" style="object-fit: cover; height: 100%; width: 100%;" referrerpolicy="no-referrer">
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#ccc" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $rRental->similarity_score }}% Match!</span>
                            </div>
                            <h6 class="fw-bold mb-1">
                                {{ Str::limit(str_replace('View listing', '', $rRental->property_name), 40) }}
                                @if($rRental->is_closed || !$rRental->is_available)
                                    <span class="badge bg-danger bg-opacity-10 text-danger ms-1">Occupied</span>
                                @endif
                            </h6>
                            <p class="fw-bold text-success mb-2" style="font-size: 1rem;">
                                RM {{ number_format($rRental->rent_amount, 2) }} 
                            </p>
                            <p class="text-muted small mb-3 text-truncate">📍 {{ Str::limit($rRental->address, 30) }}</p>

                            <a href="{{ route('public.rentals.show', $rRental->id) }}" class="btn btn-sm btn-outline-primary mt-auto">
                                View Details 🔍
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <hr class="my-5">
@endif-->

{{-- Rentals Grid --}}
<div class="row g-4">
    @forelse ($rentals as $rental)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-card">
                @if($rental->image_url && !str_contains($rental->image_url, 'image-fallback') && !str_contains($rental->image_url, 'placeholder'))
                    <div style="height: 200px; overflow: hidden;">
                        <img src="{{ $rental->image_url }}" class="card-img-top" alt="{{ $rental->property_name }}" style="object-fit: cover; height: 100%; width: 100%;" referrerpolicy="no-referrer">
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                        </svg>
                    </div>
                @endif
                <div class="card-body d-flex flex-column p-4">
                    {{-- Favorite Button --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $rental->property_type }}</span>
                            @if(isset($rental->similarity_score) && $rental->similarity_score >= 70)
                                <br><span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $rental->similarity_score }}% Match</span>
                            @endif
                        </div>
                        @auth
                            @if(in_array($rental->id, $favoriteRentalIds))
                                {{-- Remove from Favorites --}}
                                <form method="POST" action="{{ route('favorites.rentals.remove', $rental->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                {{-- Add to Favorites --}}
                                <form method="POST" action="{{ route('favorites.rentals.add', $rental->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary border-0" title="Login to favorite">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                            </a>
                        @endguest
                    </div>

                    {{-- Property Info --}}
                    <h5 class="fw-bold mb-2">
                        {{ Str::limit(str_replace('View listing', '', $rental->property_name), 50) }}
                        @if($rental->is_closed || !$rental->is_available)
                            <span class="badge bg-danger bg-opacity-10 text-danger ms-1 fs-6">Occupied</span>
                        @endif
                    </h5>
                    <p class="text-muted mb-2 small">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                        {{ Str::limit($rental->address, 40) }}
                    </p>
                    
                    {{-- Price --}}
                    <p class="fw-bold text-success mb-2" style="font-size: 1.1rem;">
                        RM {{ number_format($rental->rent_amount, 2) }} 
                        <small class="text-muted fw-normal">/ month</small>
                    </p>

                    {{-- Beds & Baths --}}
                    <p class="text-muted small mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M3 5a2 2 0 0 0-2 2v2h2V7a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h2V7a2 2 0 0 0-2-2H3z"/>
                            <path d="M1 11a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-2zm2 0v1h10v-1H3z"/>
                        </svg>
                        {{ $rental->bedrooms ?? '-' }} beds
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 1 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                        </svg>
                        {{ $rental->bathrooms ?? '-' }} baths
                    </p>

                    {{-- View Details Button --}}
                    <a href="{{ route('public.rentals.show', $rental->id) }}" 
                       class="btn btn-primary mt-auto">
                        View Details
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="ms-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                    </svg>
                    <h5 class="text-muted">No rental accommodations found</h5>
                    <p class="text-muted mb-0">Try adjusting your search or filters</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}
</style>

<script>
document.getElementById('nearMeBtn').addEventListener('click', function () {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            document.getElementById('geoForm').submit();
        },
        function () {
            alert('Location access denied. Please allow location access.');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
});
</script>

@endsection
