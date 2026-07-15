@extends('layouts.template')

@section('content')

{{-- Page Header --}}
<div class="mb-4">
    <h2 class="fw-bold mb-2" style="color: #1f0822;">Explore Internships</h2>
    <p class="text-muted">Discover your internship opportunity</p>
</div>

{{-- Banners --}}
@if($hasPreferences && !$exploreMode)
    {{-- Personalization active banner --}}
    <div class="card border-0 shadow-sm mb-4 glass-card" style="border-left: 6px solid #198754 !important; border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h5 class="fw-bold mb-1" style="color: #198754;">✨ Recommended For You</h5>
                <p class="text-muted mb-0 small">
                    Showing recommended internships based on your:
                    @if(!empty($preferences->course_of_study))
                        <strong>Course:</strong> {{ $preferences->course_of_study }}
                    @endif
                    @if(!empty($preferences->preferred_industries))
                        | <strong>Industries:</strong> {{ implode(', ', $preferences->preferred_industries) }}
                    @endif 
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('onboarding.step1') }}" class="btn btn-sm btn-outline-primary py-2 px-3" style="border-radius: 10px; white-space: nowrap;">
                    ⚙️ Edit Preferences
                </a>
                <a href="{{ route('public.internships.index', ['explore' => 1]) }}" class="btn btn-sm btn-primary py-2 px-3" style="border-radius: 10px; white-space: nowrap;">
                    🌐 Explore All
                </a>
            </div>
        </div>
    </div>
@elseif($hasPreferences && $exploreMode)
    {{-- Exploration mode active banner --}}
    <div class="card border-0 shadow-sm mb-4 glass-card" style="border-left: 6px solid #17a2b8 !important; border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h5 class="fw-bold mb-1" style="color: #17a2b8;">🌐 Exploration Mode</h5>
                <p class="text-muted mb-0 small">
                    Browsing all available internships. You can switch back to view your personalized recommendations.
                </p>
            </div>
            <div>
                <a href="{{ route('public.internships.index', ['explore' => 0]) }}" class="btn btn-sm btn-primary py-2 px-3" style="border-radius: 10px; white-space: nowrap;">
                    🎯 Back to Recommended
                </a>
            </div>
        </div>
    </div>
@elseif(auth()->check() && !$hasPreferences)
    {{-- Incomplete preferences banner --}}
    <div class="alert border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between p-4" style="border-left: 6px solid #6f42c1 !important; border-radius: 16px; background-color: rgba(243, 235, 252, 0.85);">
        <div>
            <h5 class="fw-bold text-dark mb-1">🎯 Want Personalized Internship Recommendations?</h5>
            <p class="text-muted mb-0 small">
                Set your course of study and preferences to unlock tailored recommendations for your field.
            </p>
        </div>
        <a href="{{ route('onboarding.step1') }}" class="btn btn-primary fw-semibold text-nowrap ms-3 py-2 px-3" style="border-radius: 10px;">
            Update Preferences ⚙️
        </a>
    </div>
@endif

{{-- Search & Filter Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('public.internships.index') }}">
            <input type="hidden" name="explore" value="{{ $exploreMode ? '1' : '0' }}">
            <div class="row g-3">
                @if($hasPreferences && !$exploreMode)
                    {{-- Personalized Filters --}}
                    <div class="col-lg-5 col-md-6 col-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="Search recommended internships..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <select name="state" class="form-select">
                            <option value="">All States (Preferences)</option>
                            @foreach($states as $opt)
                                <option value="{{ $opt }}" {{ request('state') === $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 col-12">
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="highest_match" {{ $sort === 'highest_match' ? 'selected' : '' }}>Highest Match</option>
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 col-12">
                        <button class="btn btn-primary w-100" type="submit">
                            Filter
                        </button>
                    </div>
                @else
                    {{-- Original Filters --}}
                    <div class="col-lg-5 col-md-6 col-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="Search by title, company, or location..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <select name="industry" class="form-select">
                            <option value="">All Fields / Courses</option>
                            @foreach($industries as $opt)
                                <option value="{{ $opt }}" {{ request('industry') === $opt ? 'selected' : '' }}>
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
                        <button class="btn btn-primary w-100" type="submit">
                            Filter
                        </button>
                    </div>
                @endif
            </div>
            @if(request('search') || request('industry') || request('state') || request('sort'))
                <div class="mt-3">
                    <a href="{{ route('public.internships.index', ['explore' => $exploreMode ? '1' : '0']) }}" class="btn btn-sm btn-outline-secondary">
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

@if($hasPreferences && !$exploreMode)
    {{-- PERSONALIZED VIEWS --}}
    @if($internships->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                </svg>
                <h5 class="text-muted">No recommendations found matching preferences</h5>
                <p class="text-muted mb-4">Try adjusting your filters, or edit your saved preferences to get better recommendations.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('onboarding.step1') }}" class="btn btn-outline-primary" style="border-radius: 8px;">
                        ⚙️ Edit Preferences
                    </a>
                    <a href="{{ route('public.internships.index', ['explore' => 1]) }}" class="btn btn-primary" style="border-radius: 8px;">
                        Explore All Internships &rarr;
                    </a>
                </div>
            </div>
        </div>
    @else
        @if($sort === 'highest_match')
            {{-- Grouped View --}}
            @foreach($groupedRecommendations as $percentage => $items)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge px-3 py-2 rounded-pill fw-bold me-3 fs-6" style="background-color: rgba(25, 135, 84, 0.1); color: #198754; border: 1px solid rgba(25, 135, 84, 0.2);">
                            🎯 {{ $percentage }}% Match
                        </span>
                        <div class="flex-grow-1 border-bottom" style="border-color: rgba(111, 66, 193, 0.15) !important;"></div>
                    </div>
                    
                    <div class="row g-4">
                        @foreach($items as $internship)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100 hover-card" style="border-top: 5px solid #198754 !important; border-radius: 16px;">
                                    <div class="card-body d-flex flex-column p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $internship->industry }}</span>
                                                @if($internship->is_closed)
                                                    <br><span class="badge bg-danger bg-opacity-10 text-danger fw-bold mt-1">Closed</span>
                                                @endif
                                            </div>
                                            @auth
                                                @if(in_array($internship->id, $favoriteInternshipIds))
                                                    <form method="POST" action="{{ route('favorites.internships.remove', $internship->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm text-danger border-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('favorites.internships.add', $internship->id) }}">
                                                        @csrf
                                                        <button class="btn btn-sm btn-outline-danger border-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        
                                        <h5 class="fw-bold mb-2" style="color: #1f0822;">{{ $internship->internship_name }}</h5>
                                        <p class="text-muted mb-2 small">🏢 <strong>{{ $internship->company }}</strong></p>
                                        <p class="text-muted mb-3 small">📍 {{ $internship->location }}</p>
                                        
                                        <a href="{{ route('public.internships.show', $internship->id) }}" class="btn btn-primary mt-auto py-2" style="border-radius: 8px;">
                                            View Details &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            {{-- Latest View --}}
            <div class="row g-4">
                @foreach($internships as $internship)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 hover-card" style="border-top: 5px solid #6f42c1 !important; border-radius: 16px;">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $internship->industry }}</span>
                                        <br><span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $internship->similarity_score }}% Match</span>
                                        @if($internship->is_closed)
                                            <br><span class="badge bg-danger bg-opacity-10 text-danger fw-bold mt-1">Closed</span>
                                        @endif
                                    </div>
                                    @auth
                                        @if(in_array($internship->id, $favoriteInternshipIds))
                                            <form method="POST" action="{{ route('favorites.internships.remove', $internship->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm text-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('favorites.internships.add', $internship->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                
                                <h5 class="fw-bold mb-2" style="color: #1f0822;">{{ $internship->internship_name }}</h5>
                                <p class="text-muted mb-2 small">🏢 <strong>{{ $internship->company }}</strong></p>
                                <p class="text-muted mb-3 small">📍 {{ $internship->location }}</p>
                                
                                <a href="{{ route('public.internships.show', $internship->id) }}" class="btn btn-primary mt-auto py-2" style="border-radius: 8px;">
                                    View Details &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Explore All button at the bottom --}}
        <div class="text-center mt-5 mb-4">
            <a href="{{ route('public.internships.index', ['explore' => 1]) }}" class="btn btn-lg btn-outline-primary px-4 py-3" style="border-radius: 12px; font-size: 1.1rem;">
                🌐 Explore All Internships
            </a>
        </div>
    @endif

@else
    {{-- GUEST / EXPLORATION / INCOMPLETE PREFERENCES VIEW --}}
    <!--@if(auth()->check() && (auth()->user()->role === 'user' || auth()->user()->role === 'student'))
        @if(isset($recommendedInternships) && $recommendedInternships->isNotEmpty())
            <div class="mb-4">
                <h4 class="fw-bold mb-3" style="color: #198754;"> 
                    🔥 Recommended for your Course: <span class="text-dark">{{ $courseOfStudy }}</span>
                </h4>
                <div class="row g-4">
                    @foreach($recommendedInternships as $rInternship)
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 hover-card" style="border-top: 5px solid #198754 !important; border-radius: 16px;">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(111, 66, 193, 0.1); color: #198754;">{{ $rInternship->similarity_score }}% Match!</span>
                                    </div>
                                    <h5 class="fw-bold mb-2" style="color: #1f0822;">{{ $rInternship->internship_name }}</h5>
                                    <p class="text-muted small mb-2"><strong>🏢 {{ $rInternship->company }}</strong></p>
                                    <p class="text-muted small mb-3 text-truncate">💼 {{ $rInternship->industry }}</p>

                                    <a href="{{ route('public.internships.show', $rInternship->id) }}" class="btn btn-sm btn-outline-primary mt-auto py-2" style="border-radius: 8px; font-weight: 600;">
                                        View Details &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr class="my-5">
        @endif
    @endif-->

    {{-- Internships Grid --}}
    <div class="row g-4">
        @forelse($internships as $internship)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 16px;">
                    <div class="card-body d-flex flex-column p-4">
                        {{-- Favorite Button & Badges --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $internship->industry }}</span>
                                @if(isset($internship->similarity_score) && $internship->similarity_score > 0)
                                    <br><span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $internship->similarity_score }}% Match</span>
                                @endif
                                @if($internship->is_closed)
                                    <br><span class="badge bg-danger bg-opacity-10 text-danger fw-bold mt-1">Closed</span>
                                @endif
                            </div>
                            @auth
                                @if(in_array($internship->id, $favoriteInternshipIds))
                                    <form method="POST" action="{{ route('favorites.internships.remove', $internship->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm text-danger border-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('favorites.internships.add', $internship->id) }}">
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

                        {{-- Internship Info --}}
                        <h5 class="fw-bold mb-2" style="color: #1f0822;">{{ $internship->internship_name }}</h5>
                        <p class="text-muted mb-2 small">
                            <strong>🏢 {{ $internship->company }}</strong>
                        </p>
                        <p class="text-muted mb-3 small">
                            📍 {{ $internship->location }}
                        </p>

                        {{-- View Details Button --}}
                        <a href="{{ route('public.internships.show', $internship->id) }}" 
                           class="btn btn-primary mt-auto py-2" style="border-radius: 8px;">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                        </svg>
                        <h5 class="text-muted">No internships found</h5>
                        <p class="text-muted mb-0">Try adjusting your search or filters</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endif

<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}
</style>

@endsection
