@extends('layouts.template')

@section('header_styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
    #map { height: 400px; width: 100%; border-radius: 12px; }
    @media (max-width: 768px) {
        #map { height: 280px; }
    }
    .rental-mini-card { transition: transform 0.2s; border-left: 4px solid #6f42c1; }
    .rental-mini-card:hover { transform: translateX(5px); background: #f8f9fa; }
    .btn-browse-rentals {
        transition: all 0.2s ease;
    }
    .btn-browse-rentals:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.25) !important;
    }
    
    /* Premium modern styling for Routing instructions */
    .leaflet-routing-container {
        background-color: white !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(31, 8, 34, 0.15) !important;
        border: none !important;
        font-family: Inter, system-ui, -apple-system, sans-serif !important;
        max-height: 280px !important;
        overflow-y: auto !important;
        padding: 12px !important;
        width: 300px !important;
        border-left: 4px solid #6f42c1 !important;
    }
    @media (max-width: 576px) {
        .leaflet-routing-container {
            width: 240px !important;
            max-height: 180px !important;
            font-size: 11px !important;
        }
    }
    .leaflet-routing-alt {
        font-size: 13px !important;
        line-height: 1.5 !important;
        padding: 0 !important;
    }
    .leaflet-routing-alt h2 {
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #1f0822 !important;
        margin-bottom: 8px !important;
        border-bottom: 1px solid #f0f0f0 !important;
        padding-bottom: 6px !important;
    }
    .leaflet-routing-alt h3 {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #6f42c1 !important;
    }
    .leaflet-routing-icon {
        opacity: 0.7;
    }
</style>

@section('content')
<div class="container">
    <a href="{{ route('public.internships.index') }}" class="btn btn-secondary mb-4 px-4">
        ← Back to Internships 💼
    </a>

    <div class="row g-4">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    {{-- Internship Name + Favorite Button --}}
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h2 class="fw-bold mb-0 text-purple">
                            {{ $internship->internship_name }}
                            @if($internship->is_closed)
                                <span class="badge bg-danger bg-opacity-10 text-danger fw-bold fs-6 ms-2 align-middle">Closed</span>
                            @endif
                        </h2>
                        @auth
                            @if(in_array($internship->id, $favoriteInternshipIds))
                                <form method="POST" action="{{ route('favorites.internships.remove', $internship->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('favorites.internships.add', $internship->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="fs-5 text-muted mb-4">🏢 {{ $internship->company }}</p>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>📍 Location:</strong> {{ $internship->location }}</p>
                            <p class="mb-2"><strong>🏭 Industry:</strong> {{ $internship->industry }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>👤 Contact Person:</strong> {{ $internship->contact_person ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>✉️ Contact Email:</strong> {{ $internship->contact_email ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>📞 Contact Phone:</strong> {{ $internship->contact_phone ?? 'N/A' }}</p>
                        </div> 

                    </div>

                    @if(!empty($internship->source_url))
                        @if($internship->is_closed)
                            <button class="btn btn-secondary px-5 py-2" disabled>
                                Currently Unavailable ✕
                            </button>
                        @else
                            <a href="{{ $internship->source_url }}" target="_blank" class="btn btn-primary px-5 py-2">
                                Apply on {{ $internship->source && $internship->source !== 'Manual' ? $internship->source : 'Direct' }} 🚀
                            </a>
                        @endif
                    @endif

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Job Description 📝</h5>
                    @php
                        $formattedDesc = \App\Services\JobDescriptionFormatter::format($internship->description);
                    @endphp

                    @if($formattedDesc['should_collapse'])
                        <div class="description-wrapper">
                            {!! $formattedDesc['summary_html'] !!}
                            <div id="desc-full" class="d-none mt-2">
                                {!! $formattedDesc['rest_html'] !!}
                            </div>
                            <button id="btn-toggle-desc" class="btn btn-link text-primary p-0 mt-2 fw-semibold d-flex align-items-center gap-1" style="text-decoration: none; outline: none; box-shadow: none; color: #6f42c1 !important;">
                                <span>Read More</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16" id="toggle-chevron" style="transition: transform 0.2s; color: #6f42c1 !important;">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <div>
                            {!! $formattedDesc['summary_html'] !!}
                        </div>
                    @endif


                </div>
            </div>

            <!-- Map Section -->
            @if($internship->lat && $internship->lng)
                <div class="card shadow-sm border-0 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-purple d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                            </svg>
                            Interactive Map & Navigation
                        </h5>
                        <span id="location-status" class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 fs-7">No current location</span>
                    </div>
                    <div id="map"></div>
                    <div class="card-footer bg-light border-top p-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button id="btn-locate" class="btn btn-primary btn-sm d-flex align-items-center gap-2 fw-semibold px-3 py-2 shadow-sm transition-all hover-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                Locate Me & Route 📍
                            </button>
                            <button id="btn-clear-route" class="btn btn-danger btn-sm d-flex align-items-center gap-2 fw-semibold px-3 py-2 shadow-sm transition-all" style="display: none !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                                Clear Route ✕
                            </button>
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $internship->lat }},{{ $internship->lng }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 fw-semibold px-3 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            Open in Google Maps ➔
                        </a>
                    </div>
                </div>
            @endif

            <!-- Reviews Section -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-purple mb-0">Reviews 💬</h4>
                        @auth
                            <button type="button" class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                                Write Review ✍️
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2">
                                Write Review ✍️
                            </a>
                        @endauth
                    </div>

                    @php
                        $avgRating = $internship->reviews->avg('rating') ?? 0;
                        $totalReviews = $internship->reviews->count();
                        $roundedRating = (int) round($avgRating);
                    @endphp

                    @if($totalReviews > 0)
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.55); border: 1px solid rgba(255,255,255,0.4);">
                            <div class="text-center border-end pe-4">
                                <h1 class="fw-extrabold text-dark mb-0" style="font-size: 2.5rem;">{{ number_format($avgRating, 1) }}</h1>
                                <small class="text-muted">out of 5</small>
                            </div>
                            <div>
                                <div class="fs-4 text-warning mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $roundedRating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <span class="fw-semibold text-secondary">{{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }}</span>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        <div class="review-list">
                            @foreach($internship->reviews as $review)
                                <div class="card border border-light-subtle mb-3 shadow-none">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle-sm d-flex align-items-center justify-content-center fw-bold text-primary bg-primary bg-opacity-10 rounded-circle" style="width: 36px; height: 36px; font-size: 14px;">
                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $review->user->name }}</h6>
                                                    <small class="text-muted" style="font-size: 11px;">{{ $review->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                            <div class="text-warning" style="font-size: 14px;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-secondary mb-0" style="white-space: pre-wrap; font-size: 13.5px; line-height: 1.5;">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-3">
                            <div class="fs-1 text-muted mb-2">💬</div>
                            <h5 class="fw-semibold text-secondary">No reviews available yet</h5>
                            <p class="small text-muted mb-0">Be the first to share your experience!</p>
                        </div>
                    @endif
                </div>
            </div>


        </div>

        <!-- Sidebar: Nearby Rentals -->
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">Nearby Accommodation (15km)</h5>
            
            @forelse($nearbyRentals as $rental)
                <div class="card mb-3 border-0 shadow-sm rental-mini-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">
                            {{ $rental->property_name }}
                            @if($rental->is_closed || !$rental->is_available)
                                <span class="badge bg-danger bg-opacity-10 text-danger ms-1" style="font-size: 0.7rem;">Occupied</span>
                            @endif
                        </h6>
                        <p class="small text-muted mb-2">
                            {{ number_format($rental->distance, 1) }} km away
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success">RM {{ number_format($rental->rent_amount, 2) }}</span>
                            <a href="{{ route('public.rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    No rentals found nearby. Try broadening your search.
                </div>
            @endforelse
            
            <div class="mt-4 p-4 bg-white border border-light-subtle rounded shadow-sm text-center">
                <p class="text-muted small mb-3">Wanna see more options?</p>
                <a href="{{ route('public.rentals.index') }}" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 btn-browse-rentals">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.507c0-.3.245-.543.547-.543h2.806c.3 0 .547.243.547.543v3.507c0 .193-.155.35-.347.35H6.847a.35.35 0 0 1-.347-.35z"/>
                        <path d="M2.3 7a1 1 0 0 0-.4.8v5.2c0 .6.4 1 1 1h10c.6 0 1-.4 1-1V7.8a1 1 0 0 0-.4-.8L8 2.2 2.3 7z"/>
                    </svg>
                    Browse all rentals
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Write Review Modal -->
@auth
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('public.internships.reviews.store', $internship->id) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary" id="writeReviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Rating Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary mb-1">Rating</label>
                        <div class="rating-stars-input d-flex gap-2 fs-2">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star-btn text-secondary" data-value="{{ $i }}" style="cursor: pointer; transition: transform 0.1s; user-select: none;">☆</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="{{ old('rating') }}" required>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Comment Input -->
                    <div class="mb-3">
                        <label for="comment" class="form-label fw-semibold text-secondary mb-1">Comment</label>
                        <textarea class="form-control border-light-subtle" name="comment" id="comment" rows="4" maxlength="500" required placeholder="Share your experience (max 500 characters)..." style="border-radius: 10px; font-size: 14px;">{{ old('comment') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            @error('comment')
                                <div class="text-danger small">{{ $message }}</div>
                            @else
                                <div></div>
                            @enderror
                            <small class="text-muted"><span id="char-count">0</span>/500</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
@if($internship->lat && $internship->lng)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const internshipLat = {{ $internship->lat }};
        const internshipLng = {{ $internship->lng }};
        
        const map = L.map('map').setView([internshipLat, internshipLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Core variables for Routing & Location
        let routingControl = null;
        let userMarker = null;
        let userCoords = null;

        // Custom marker icons
        const internshipIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const rentalIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const transitIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Initialize Map Bounds to dynamically fit all markers
        const mapBounds = L.latLngBounds([[internshipLat, internshipLng]]);

        // 1. Internship Marker (Red)
        const internshipPopupHTML = `
            <div class="p-1">
                <h6 class="fw-bold mb-1 text-primary">📍 {{ addslashes($internship->internship_name) }}</h6>
                <p class="small text-muted mb-2">
                    <b>Company:</b> {{ addslashes($internship->company) }}<br>
                    <b>Location:</b> {{ addslashes($internship->location) }}
                </p>
                <div class="d-flex gap-1 mt-2">
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${internshipLat},${internshipLng}" target="_blank" class="btn btn-xs btn-primary text-white d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                        Navigate
                    </a>
                    <button onclick="drawRouteTo(${internshipLat}, ${internshipLng}, '{{ addslashes($internship->company) }}')" class="btn btn-xs btn-outline-primary d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                        Route Here
                    </button>
                </div>
            </div>
        `;
        L.marker([internshipLat, internshipLng], {icon: internshipIcon})
            .addTo(map)
            .bindPopup(internshipPopupHTML)
            .openPopup();

        // 2. Rental Accommodation Markers (Green) - Limit to 3 nearest
        @foreach($nearbyRentals->take(3) as $rental)
            @if($rental->lat && $rental->lng)
                const rentalPopupHTML_{{ $rental->id }} = `
                    <div class="p-1">
                        <h6 class="fw-bold mb-1 text-success">🏠 {{ addslashes($rental->property_name) }}</h6>
                        <p class="small text-muted mb-2">
                            <b>Rent:</b> RM {{ number_format($rental->rent_amount, 2) }}/month<br>
                            <b>Distance:</b> {{ number_format($rental->distance, 1) }} km away
                        </p>
                        <div class="d-flex gap-1 mt-2">
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $rental->lat }},{{ $rental->lng }}" target="_blank" class="btn btn-xs btn-primary text-white d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                                Navigate
                            </a>
                            <button onclick="drawRouteTo({{ $rental->lat }}, {{ $rental->lng }}, '{{ addslashes($rental->property_name) }}')" class="btn btn-xs btn-outline-success d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                                Route Here
                            </button>
                        </div>
                    </div>
                `;
                L.marker([{{ $rental->lat }}, {{ $rental->lng }}], {icon: rentalIcon})
                    .addTo(map)
                    .bindPopup(rentalPopupHTML_{{ $rental->id }});

                mapBounds.extend([{{ $rental->lat }}, {{ $rental->lng }}]);
            @endif
        @endforeach

        // Fit initial bounds to include internship + rental markers
        map.fitBounds(mapBounds, { padding: [50, 50], maxZoom: 15 });

        // Helper: Haversine distance calculator in JS
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                Math.sin(dLon/2) * Math.sin(dLon/2); 
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            return R * c;
        }

        // 3. Dynamic Public Transit stops (Blue) - Overpass API
        async function loadNearbyTransit() {
            const query = `[out:json];node(around:2500,${internshipLat},${internshipLng})[railway~"station|halt|stop"];out;`;
            const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;

            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.elements && data.elements.length > 0) {
                    data.elements.forEach(element => {
                        const name = element.tags.name || "Transit Station";
                        const operator = element.tags.operator || "";
                        const distToJob = calculateDistance(internshipLat, internshipLng, element.lat, element.lon);
                        
                        const transitPopupHTML = `
                            <div class="p-1">
                                <h6 class="fw-bold mb-1 text-info">🚉 ${name}</h6>
                                <p class="small text-muted mb-2">
                                    <b>Operator:</b> ${operator || 'KTM/LRT/MRT'}<br>
                                    <b>Distance to Job:</b> ${distToJob.toFixed(2)} km
                                </p>
                                <div class="d-flex gap-1 mt-2">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${element.lat},${element.lon}" target="_blank" class="btn btn-xs btn-primary text-white d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                                        Navigate
                                    </a>
                                    <button onclick="drawRouteTo(${element.lat}, ${element.lon}, '${name.replace(/'/g, "\\'")}')" class="btn btn-xs btn-outline-info d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 8px;">
                                        Route Here
                                    </button>
                                </div>
                            </div>
                        `;
                        L.marker([element.lat, element.lon], {icon: transitIcon})
                            .addTo(map)
                            .bindPopup(transitPopupHTML);

                        mapBounds.extend([element.lat, element.lon]);
                    });

                    // Re-fit bounds with new transit stops included
                    map.fitBounds(mapBounds, { padding: [50, 50], maxZoom: 15 });
                }
            } catch (error) {
                console.error("Failed to fetch transit data:", error);
            }
        }

        loadNearbyTransit();

        // 4. Geolocation and User Tracking
        function locateUser(callback) {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            const statusBadge = document.getElementById('location-status');
            statusBadge.innerText = 'Locating...';
            statusBadge.className = 'badge bg-warning bg-opacity-10 text-warning px-3 py-2 fs-7';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userCoords = [position.coords.latitude, position.coords.longitude];
                    
                    statusBadge.innerText = 'Location Detected';
                    statusBadge.className = 'badge bg-success bg-opacity-10 text-success px-3 py-2 fs-7';

                    // Draw/Update user marker
                    if (userMarker) {
                        userMarker.setLatLng(userCoords);
                    } else {
                        userMarker = L.marker(userCoords, {icon: userIcon})
                            .addTo(map)
                            .bindPopup('<b>🌟 Your Location</b>');
                    }
                    userMarker.openPopup();

                    if (callback) callback(userCoords);
                },
                function(error) {
                    statusBadge.innerText = 'Location Access Denied';
                    statusBadge.className = 'badge bg-danger bg-opacity-10 text-danger px-3 py-2 fs-7';
                    alert('Could not retrieve your location. Please ensure location services are enabled.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        // 5. Leaflet Routing Machine implementation
        window.drawRouteTo = function(destLat, destLng, destName) {
            if (!userCoords) {
                locateUser(function(coords) {
                    performRouting(coords, [destLat, destLng], destName);
                });
            } else {
                performRouting(userCoords, [destLat, destLng], destName);
            }
        };

        function performRouting(start, end, destName) {
            // Show clear route button
            document.getElementById('btn-clear-route').style.setProperty('display', 'inline-flex', 'important');

            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(start[0], start[1]),
                    L.latLng(end[0], end[1])
                ],
                routeWhileDragging: false,
                collapsible: true,
                lineOptions: {
                    styles: [{ color: '#6f42c1', weight: 6, opacity: 0.8 }]
                },
                createMarker: function() { return null; } // Prevent duplicate markers
            }).addTo(map);

            const statusBadge = document.getElementById('location-status');
            statusBadge.innerHTML = `Route to ${destName}`;
            statusBadge.className = 'badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-7';
        }

        // Button Click Handlers
        document.getElementById('btn-locate').addEventListener('click', function() {
            locateUser(function(coords) {
                performRouting(coords, [internshipLat, internshipLng], '{{ addslashes($internship->company) }}');
            });
        });

        document.getElementById('btn-clear-route').addEventListener('click', function() {
            if (routingControl) {
                map.removeControl(routingControl);
                routingControl = null;
            }
            
            document.getElementById('btn-clear-route').style.setProperty('display', 'none', 'important');
            
            const statusBadge = document.getElementById('location-status');
            statusBadge.innerText = 'Route cleared';
            statusBadge.className = 'badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 fs-7';
            
            if (userCoords) {
                map.setView(userCoords, 13);
            } else {
                map.setView([internshipLat, internshipLng], 13);
            }
        });
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.rating-stars-input .star-btn');
        const ratingInput = document.getElementById('rating-value');
        
        // Function to update stars visually
        function updateStars(val) {
            stars.forEach(s => {
                const sVal = s.getAttribute('data-value');
                if (sVal <= val) {
                    s.innerText = '★';
                    s.classList.add('text-warning');
                    s.classList.remove('text-secondary');
                } else {
                    s.innerText = '☆';
                    s.classList.remove('text-warning');
                    s.classList.add('text-secondary');
                }
            });
        }

        // Initialize stars if old input exists
        if (ratingInput && ratingInput.value) {
            updateStars(ratingInput.value);
        }

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                if (ratingInput) ratingInput.value = val;
                updateStars(val);
            });

            star.addEventListener('mouseover', function() {
                const val = this.getAttribute('data-value');
                updateStars(val);
            });
        });

        const container = document.querySelector('.rating-stars-input');
        if (container) {
            container.addEventListener('mouseleave', function() {
                const val = ratingInput ? ratingInput.value : 0;
                updateStars(val);
            });
        }

        const commentArea = document.getElementById('comment');
        const charCount = document.getElementById('char-count');
        if (commentArea && charCount) {
            commentArea.addEventListener('input', function() {
                charCount.innerText = this.value.length;
            });
            charCount.innerText = commentArea.value.length;
        }

        // Description expand/collapse toggle
        const btnToggleDesc = document.getElementById('btn-toggle-desc');
        if (btnToggleDesc) {
            const descFull = document.getElementById('desc-full');
            const chevron = document.getElementById('toggle-chevron');
            
            btnToggleDesc.addEventListener('click', function() {
                const isCollapsed = descFull.classList.contains('d-none');
                if (isCollapsed) {
                    descFull.classList.remove('d-none');
                    btnToggleDesc.querySelector('span').textContent = 'Show Less';
                    if (chevron) {
                        chevron.style.transform = 'rotate(180deg)';
                    }
                } else {
                    descFull.classList.add('d-none');
                    btnToggleDesc.querySelector('span').textContent = 'Read More';
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }
                    btnToggleDesc.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }

        @if($errors->has('rating') || $errors->has('comment'))
            const writeReviewModal = new bootstrap.Modal(document.getElementById('writeReviewModal'));
            if (writeReviewModal) {
                writeReviewModal.show();
            }
        @endif
    });
</script>
@endpush

