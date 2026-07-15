@extends('layouts.template')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold mb-2" style="color: #1f0822;">My Favourites</h2>
        <p class="text-muted mb-0">Your saved internships and rental accommodations</p>
    </div>
    <a href="{{ route('public.internships.index') }}" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg>
        Explore Internships
    </a>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Internship Favourites --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
            </svg>
            Internship Favourites
        </h5>

        @forelse($internshipFavorites as $fav)
            @php $intern = $fav->favoritable; @endphp
            @if($intern)
            <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.25);">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 text-purple">{{ $intern->internship_name ?? '-' }}</h6>
                    <p class="text-muted small mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                        </svg>
                        {{ $intern->company ?? '-' }}
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                        {{ $intern->location ?? '-' }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('public.internships.show', $intern->id) }}" class="btn btn-sm btn-outline-primary">View 🔍</a>
                    <form method="POST" action="{{ route('favorites.internships.remove', $intern->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Remove ✕</button>
                    </form>
                </div>
            </div>
            @endif
        @empty
            <div class="text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                </svg>
                <p class="text-muted">No favourite internships yet</p>
                <a href="{{ route('public.internships.index') }}" class="btn btn-sm btn-primary">Browse Internships 💼</a>
            </div>
        @endforelse
    </div>
</div>

{{-- Rental Favourites --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
            </svg>
            Rental Accommodation Favourites
        </h5>

        @forelse($rentalFavorites as $fav)
            @php $rental = $fav->favoritable; @endphp
            @if($rental)
            <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.25);">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 text-purple">{{ $rental->property_name }}</h6>
                    <p class="text-muted small mb-0">
                        <span class="badge bg-secondary bg-opacity-10 text-dark me-2">{{ $rental->property_type }}</span>
                        📍 {{ $rental->address }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('public.rentals.show', $rental->id) }}" class="btn btn-sm btn-outline-primary">View 🔍</a>
                    <form method="POST" action="{{ route('favorites.rentals.remove', $rental->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Remove ✕</button>
                    </form>
                </div>
            </div>
            @endif
        @empty
            <div class="text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                </svg>
                <p class="text-muted">No favourite rentals yet</p>
                <a href="{{ route('public.rentals.index') }}" class="btn btn-sm btn-primary">Browse Rentals</a>
            </div>
        @endforelse
    </div>
</div>

@endsection
