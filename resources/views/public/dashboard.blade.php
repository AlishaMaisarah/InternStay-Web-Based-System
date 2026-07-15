@extends('layouts.template')

@section('content')

{{-- Onboarding Reminder Banner --}}
@auth
    @if(!Auth::user()->onboarding_completed)
    <div class="alert alert-info fade show mb-4 border-0 shadow-sm p-3"
     role="alert"
     style="border-left: 5px solid #6f42c1 !important; border-radius: 12px;">

    <div class="d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="24"
             height="24"
             fill="#6f42c1"
             class="me-3 flex-shrink-0"
             viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"/>
            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
        </svg>

        <div class="flex-grow-1">
            <strong>⚡ Personalize your experience!</strong>
            Set your preferences to get better recommendations and notifications.
        </div>

        <a href="{{ route('onboarding.welcome') }}"
           class="btn btn-sm btn-primary ms-3 px-4 fw-semibold">
            Set Preferences Now
        </a>

        <button type="button"
                class="btn-close ms-3"
                data-bs-dismiss="alert"
                aria-label="Close">
        </button>
    </div>
</div>
    @endif
@endauth

{{-- Hero Section --}}
<div class="row align-items-center g-5 pt-5 pb-3 mb-3">
    <div class="col-lg-6">
        <h1 class="display-5 hero-title-clamp fw-bold mb-3 text-dark text-gradient-purple" style="line-height: 1.2;">
            Find Your Internship & Accommodation in One Place
        </h1>
        <p class="lead text-muted mb-4" style="font-size: 1.15rem; line-height: 1.6;">
            Discover amazing internship opportunities and find the perfect accommodation nearby. Smart recommendations, interactive maps, and everything you need in one platform.
        </p>
        <div class="d-flex flex-wrap gap-3 mb-4">
            <a href="{{ route('public.internships.index') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                </svg>
                Browse Internships
            </a>
            <a href="{{ route('public.rentals.index') }}" class="btn btn-outline-primary btn-lg px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center" style="border-color: #6f42c1; color: #6f42c1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                </svg>
                Find Accommodation
            </a>
        </div>
        
        {{-- Social Proof --}}
        <div class="d-flex align-items-center gap-3 mt-2">
            <div class="avatar-group d-flex">
                <span class="avatar-item bg-purple-light text-purple rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px; border: 2px solid white; font-size:12px; margin-right:-8px; z-index: 3;">A</span>
                <span class="avatar-item bg-success bg-opacity-25 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px; border: 2px solid white; font-size:12px; margin-right:-8px; z-index: 2;">M</span>
                <span class="avatar-item bg-info bg-opacity-25 text-info rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px; border: 2px solid white; font-size:12px; z-index: 1;">H</span>
            </div>
            <div>
                <div class="text-warning fs-6">
                    ★ ★ ★ ★ ★
                </div>
                <small class="text-muted fw-semibold">Trusted by students across Malaysia</small>
            </div>
        </div>
    </div>
    
    {{-- Hero Interactive Mockup --}}
    <div class="col-lg-6 d-none d-lg-block position-relative">
        <div class="mockup-container">
            {{-- Main Map Mockup --}}
            <div class="card shadow-lg border-0 mockup-map-card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                    <span class="small fw-bold text-dark">Nearby Rentals</span>
                    <span class="badge bg-success bg-opacity-10 text-success small">1.2 km away</span>
                </div>
                <div class="mockup-map-bg rounded-3 mb-2 d-flex align-items-center justify-content-center text-muted position-relative" style="height: 180px; background-color: #e9ecef; overflow:hidden;">
                    <div class="map-grid-pattern"></div>
                    <div class="pin position-absolute bg-primary rounded-circle" style="width:14px; height:14px; top:40%; left:50%; border:2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>
                    <div class="pin position-absolute bg-success rounded-circle" style="width:14px; height:14px; top:65%; left:30%; border:2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>
                    <span class="small fw-semibold text-secondary position-relative" style="z-index: 10;">Interactive Map Search</span>
                </div>
            </div>
            
            {{-- Stacked Card 1 (Internship Detail) --}}
            <div class="card shadow-lg border-0 mockup-detail-card p-3" style="width: 280px; position:absolute; bottom:-40px; right: -20px; z-index: 15; border-radius:16px;">
                <div class="d-flex gap-2 align-items-start mb-2">
                    <div class="bg-purple bg-opacity-10 text-purple rounded-3 p-2 d-inline-flex" style="background-color: rgba(111,66,193,0.1); color: #6f42c1;">
                        💼
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 small">Software Engineering Intern</h6>
                        <small class="text-muted">Kuala Lumpur</small>
                    </div>
                </div>
                <div class="d-grid gap-1">
                    <div class="bg-light rounded p-2 text-start">
                        <small class="text-muted d-block" style="font-size:10px;">Distance</small>
                        <span class="fw-semibold text-dark small">850m / 5 min</span>
                    </div>
                    <button class="btn btn-primary btn-sm mt-2 fw-semibold py-1.5" style="font-size:12px; background-color: #6f42c1; border-color: #6f42c1;">Apply Now</button>
                </div>
            </div>

            {{-- Stacked Card 2 (AI Recommendation) --}}
            <div class="card shadow-lg border-0 mockup-rec-card p-3" style="width: 250px; position:absolute; top:-30px; left: -30px; z-index: 5; border-radius:16px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold small text-dark">Recommended for You</span>
                    <span class="badge bg-purple-light text-purple fw-bold" style="font-size: 10px;">95% Match!</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="fs-4">🚀</span>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 11px;">Data Analyst Intern</h6>
                        <small class="text-muted" style="font-size: 10px;">TechNova Solutions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<hr class="my-5 opacity-0">-->

{{-- SECTION 1: How InternStay Works --}}
<div class="card rounded-4 shadow-sm p-4 p-sm-5 mb-5 border-0 mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold section-heading-clamp mb-2 text-dark">How InternStay Works 💡</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Simple steps to land your ideal internship and housing</p>
    </div>

    <div class="row g-4 justify-content-center position-relative timeline-row">
        {{-- Step 1 --}}
        <div class="col-md-3 text-center position-relative z-index-2">
            <div class="bg-purple bg-opacity-10 text-purple rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px; background-color: rgba(111,66,193,0.1); color:#6f42c1;">
                <span class="fs-4">🔍</span>
            </div>
            <h6 class="fw-bold text-dark mb-2">1. Browse 📁</h6>
            <p class="text-muted small px-3">Search internships and filter by location, industry, or company name.</p>
        </div>

        {{-- Step 2 --}}
        <div class="col-md-3 text-center position-relative z-index-2">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                <span class="fs-4">🏠</span>
            </div>
            <h6 class="fw-bold text-dark mb-2">2. Find Nearby Rentals 📍</h6>
            <p class="text-muted small px-3">View available accommodations within 15km of your chosen provider.</p>
        </div>

        {{-- Step 3 --}}
        <div class="col-md-3 text-center position-relative z-index-2">
            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                <span class="fs-4">🧡</span>
            </div>
            <h6 class="fw-bold text-dark mb-2">3. Save & Compare ✨</h6>
            <p class="text-muted small px-3">Bookmark your favorites and compare matching options easily.</p>
        </div>

        {{-- Step 4 --}}
        <div class="col-md-3 text-center position-relative z-index-2">
            <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                <span class="fs-4">✈</span>
            </div>
            <h6 class="fw-bold text-dark mb-2">4. Apply & Navigate 🚀</h6>
            <p class="text-muted small px-3">Apply for internships and navigate with our interactive maps.</p>
        </div>
    </div>
</div>

{{-- SECTION 2: Why Choose InternStay --}}
<div class="card rounded-4 shadow-sm p-4 p-sm-5 mb-5 border-0">
    <div class="text-center mb-5">
        <h2 class="fw-bold section-heading-clamp mb-2 text-dark">Why Choose InternStay 🎯</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Built specifically to simplify student housing and matching</p>
    </div>
    
    <div class="row g-4 text-start">
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">⚡</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">All-in-One Platform</h6>
                    <p class="text-muted small">Access both internships and rentals in one convenient workspace.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">🎯</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Intelligent Matching</h6>
                    <p class="text-muted small">Personalized recommendations tailored to your university course.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">🗺</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Interactive & Visual</h6>
                    <p class="text-muted small">Maps, routes, and transit visual overlays for perfect locations.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">🔄</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Always Updated</h6>
                    <p class="text-muted small">Automated scraping runs weekly to fetch the latest active listings.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">✉</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Email Notifications</h6>
                    <p class="text-muted small">Get daily or weekly digest alerts of newly matched listings.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-start mb-3">
                <span class="fs-3 me-3 text-purple">🎓</span>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Student-Focused</h6>
                    <p class="text-muted small">Tailored specifically for Malaysian students seeking attachments.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 3: Featured Internships --}}
<div class="card rounded-4 shadow-sm p-4 p-sm-5 mb-5 border-0">
    <div class="text-center mb-5">
        <h2 class="fw-bold section-heading-clamp mb-2 text-dark">Featured Internships 🚀</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Explore our top active internship opportunities</p>
    </div>
    
    <div class="catalog-grid text-start">
        @forelse($featuredInternships as $internship)
            <div class="card border shadow-sm h-100 hover-lift" style="border-radius: 16px;">
                <div class="card-body d-flex flex-column p-4">
                    <span class="badge bg-purple mb-3 px-3 py-2 align-self-start text-white" style="border-radius: 8px; background-color: #6f42c1 !important; color: white !important;">
                        🏢 {{ $internship->company }}
                    </span>
                    <h5 class="fw-bold card-title-clamp text-dark mb-2">{{ $internship->internship_name }}</h5>
                    <p class="text-muted mb-2 small fw-semibold">💼 {{ $internship->industry ?? 'General Internship' }}</p>
                    <p class="text-muted small mb-4">📍 {{ $internship->location }}</p>
                    
                    <a href="{{ route('public.internships.show', $internship->id) }}" class="btn btn-outline-primary mt-auto py-2.5 fw-semibold" style="border-radius: 10px;">
                        View Details 🔍
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-4" style="grid-column: 1 / -1;">
                <p class="text-muted mb-0">No featured internships available right now.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- SECTION 4: Featured Rentals --}}
<div class="card rounded-4 shadow-sm p-4 p-sm-5 mb-5 border-0">
    <div class="text-center mb-5">
        <h2 class="fw-bold section-heading-clamp mb-2 text-dark">Featured Rentals 🏠</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Find the perfect place to stay near your internship</p>
    </div>
    
    <div class="catalog-grid text-start">
        @forelse($featuredRentals as $rental)
            <div class="card border shadow-sm h-100 hover-lift overflow-hidden" style="border-radius: 16px;">
                {{-- Thumbnail --}}
                <div class="position-relative" style="height: 200px; background-color: #f3ebfc;">
                    @if($rental->image_url)
                        <img src="{{ $rental->image_url }}" alt="{{ $rental->property_name }}" class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <span class="fs-1">🏠</span>
                        </div>
                    @endif
                    <span class="position-absolute top-3 end-3 badge bg-success px-3 py-2 fw-bold" style="top: 15px; right: 15px; border-radius: 8px;">
                        RM {{ number_format($rental->rent_amount, 2) }} /mo
                    </span>
                </div>
                
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="fw-bold card-title-clamp text-dark mb-2">{{ $rental->property_name }}</h5>
                    <p class="text-muted small mb-4">📍 {{ $rental->address }}</p>
                    
                    <a href="{{ route('public.rentals.show', $rental->id) }}" class="btn btn-outline-primary mt-auto py-2.5 fw-semibold" style="border-radius: 10px;">
                        View Details 🔍
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-4" style="grid-column: 1 / -1;">
                <p class="text-muted mb-0">No featured rentals available right now.</p>
            </div>
        @endforelse
    </div>
</div>


<style>
.text-gradient-purple {
    background: linear-gradient(135deg, #1f0822 0%, #6f42c1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.text-purple {
    color: #6f42c1 !important;
}
.bg-purple-light {
    background-color: rgba(111, 66, 193, 0.1) !important;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
.mockup-container {
    position: relative;
    height: 380px;
    width: 100%;
}
.mockup-map-card {
    width: 440px;
    height: 280px;
    position: absolute;
    top: 30px;
    left: 80px;
    border-radius: 16px;
    z-index: 10;
}
.map-grid-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: 20px 20px;
    background-image: 
        linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
}
.timeline-row::before {
    content: "";
    position: absolute;
    top: 32px;
    left: 15%;
    width: 70%;
    height: 3px;
    background: linear-gradient(90deg, #6f42c1 0%, #0dcaf0 100%);
    z-index: 1;
    display: none;
}
@media (min-width: 768px) {
    .timeline-row::before {
        display: block;
    }
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(31, 8, 34, 0.08) !important;
}
.object-fit-cover {
    object-fit: cover;
}
</style>

@endsection
