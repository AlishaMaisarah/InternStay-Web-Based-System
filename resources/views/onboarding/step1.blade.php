@extends('layouts.app')

@section('content')
{{-- Background Illustrations - MUST come BEFORE the container --}}
<div class="bg-scene" aria-hidden="true">
    <!-- atmosphere -->
    <div class="blob blob--1"></div>
    <div class="blob blob--2"></div>
    <div class="blob blob--3"></div>

    <svg width="0" height="0" style="position:absolute">
        <defs>
            <linearGradient id="gViolet" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#8b5cf6"/>
                <stop offset="100%" stop-color="#4a2a80"/>
            </linearGradient>
            <linearGradient id="gGold" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#f0c675"/>
                <stop offset="100%" stop-color="#b9791f"/>
            </linearGradient>
            <linearGradient id="gTeal" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#5fc9ba"/>
                <stop offset="100%" stop-color="#1f7a6d"/>
            </linearGradient>
            <linearGradient id="gRoute" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%"  stop-color="#6f42c1"  stop-opacity="0.5"/>
                <stop offset="50%" stop-color="#d9a441"  stop-opacity="0.5"/>
                <stop offset="100%" stop-color="#2f9e8f"  stop-opacity="0.5"/>
            </linearGradient>
        </defs>
    </svg>

    <!-- Journey line -->
    <svg class="bg-illustration" style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0.5; pointer-events:none;"
         viewBox="0 0 1440 1200" preserveAspectRatio="none" fill="none">
        <path d="M120 160
                 C 300 60, 420 260, 560 210
                 S 760 90, 900 220
                 S 1040 480, 880 560
                 S 560 620, 480 780
                 S 620 980, 420 1040"
              stroke="url(#gRoute)" stroke-width="3.5" stroke-dasharray="2 14"
              stroke-linecap="round"/>
        <circle cx="120" cy="160"  r="6" fill="#6f42c1" opacity="0.55"/>
        <circle cx="900" cy="220"  r="6" fill="#d9a441" opacity="0.55"/>
        <circle cx="880" cy="560"  r="6" fill="#2f9e8f" opacity="0.55"/>
        <circle cx="420" cy="1040" r="6" fill="#6f42c1" opacity="0.55"/>
    </svg>

    <!-- Compass -->
    <svg class="bg-illustration float" style="position:absolute; width:130px; height:130px; top:9%; left:5%; --r:-8deg; --d:0s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(74,42,128,0.15)); pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gViolet)">
        <path d="M50 5C25.1 5 5 25.1 5 50s20.1 45 45 45 45-20.1 45-45S74.9 5 50 5zm0 82c-20.4 0-37-16.6-37-37s16.6-37 37-37 37 16.6 37 37-16.6 37-37 37z"/>
        <path d="M50 25c-1.7 0-3 1.3-3 3v13.6L36.3 36.3c-1.2-1.2-3.1-1.2-4.2 0s-1.2 3.1 0 4.2L42.9 50l-9.2 9.2c-1.2 1.2-1.2 3.1 0 4.2.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9L50 56.4V70c0 1.7 1.3 3 3 3s3-1.3 3-3V56.4l10.8 10.8c.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9c1.2-1.2 1.2-3.1 0-4.2L57.1 50l9.2-9.2c1.2-1.2 1.2-3.1 0-4.2s-3.1-1.2-4.2 0L50 41.6V28c0-1.7-1.3-3-3-3z"/>
    </svg>

    <!-- Location pin -->
    <svg class="bg-illustration float" style="position:absolute; width:78px; height:78px; top:15%; left:36%; --r:6deg; --d:1.2s; opacity:0.6; filter: drop-shadow(0 6px 12px rgba(185,121,31,0.18)); pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gGold)">
        <path d="M50 2C31.2 2 16 17.2 16 36c0 23.3 30.6 59.9 31.9 61.5.6.7 1.5 1.1 2.4 1.1.9 0 1.8-.4 2.4-1.1C54.1 95.9 84 59.3 84 36 84 17.2 68.8 2 50 2zm0 50c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16z"/>
    </svg>

    <!-- Graduation cap -->
    <svg class="bg-illustration float" style="position:absolute; width:190px; height:190px; top:10%; right:6%; --r:-5deg; --d:2.1s; opacity:0.38; filter: drop-shadow(0 10px 20px rgba(31,122,109,0.15)); pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gTeal)">
        <path d="M50 15L5 35l45 20 37-16.4V65c0 2.2 1.8 4 4 4s4-1.8 4-4V35.4L50 15zm0 53.6L18 54.4V62c0 8.8 14.3 16 32 16s32-7.2 32-16v-7.6L50 68.6z"/>
    </svg>

    <!-- Briefcase -->
    <svg class="bg-illustration float" style="position:absolute; width:150px; height:150px; bottom:13%; left:3%; --r:7deg; --d:0.6s; opacity:0.6; filter: drop-shadow(0 8px 18px rgba(74,42,128,0.16)); pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gGold)">
        <path d="M85 30H70v-8c0-4.4-3.6-8-8-8H38c-4.4 0-8 3.6-8 8v8H15c-5.5 0-10 4.5-10 10v40c0 5.5 4.5 10 10 10h70c5.5 0 10-4.5 10-10V40c0-5.5-4.5-10-10-10zM36 22c0-1.1.9-2 2-2h24c1.1 0 2 .9 2 2v8H36v-8zm53 58c0 2.2-1.8 4-4 4H15c-2.2 0-4-1.8-4-4V48h78v32zm0-38H11v-2c0-2.2 1.8-4 4-4h70c2.2 0 4 1.8 4 4v2z"/>
    </svg>

    <!-- Small compass -->
    <svg class="bg-illustration float" style="position:absolute; width:75px; height:75px; top:44%; right:8%; --r:12deg; --d:1.8s; opacity:0.80; pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gGold)">
        <path d="M50 2C23.5 2 2 23.5 2 50s21.5 48 48 48 48-21.5 48-48S76.5 2 50 2zm0 88c-22.1 0-40-17.9-40-40s17.9-40 40-40 40 17.9 40 40-17.9 40-40 40zm12-40L50 26 38 50l12 12 12-12z"/>
    </svg>

    <!-- House -->
    <svg class="bg-illustration float" style="position:absolute; width:150px; height:150px; bottom:3%; left:33%; --r:-6deg; --d:0.9s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(185,121,31,0.18)); pointer-events:none;"
         viewBox="0 0 24 24" fill="url(#gTeal)">
        <path d="M12 3L2 12h3v8h6v-5h2v5h6v-8h3L12 3z"/>
    </svg>

    <!-- Building -->
    <svg class="bg-illustration float" style="position:absolute; width:150px; height:150px; bottom:2%; right:3%; --r:4deg; --d:1.5s; opacity:0.42; pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gViolet)">
        <path d="M40 2h45v96H5c2.2 0 4-1.8 4-4V35c0-2.2 1.8-4 4-4h27V2zm37 84V10H48v76h29zm-18-58h8v8h-8v-8zm0 20h8v8h-8v-8zm0 20h8v8h-8v-8zm-22-2h8v8h-8v-8zm0-20h8v8h-8v-8zm-20 40h8v8H17v-8zm0-20h8v8H17v-8z"/>
    </svg>
</div>

{{-- MAIN CONTENT - with transparent background so illustrations show through --}}
<div class="container py-5" style="min-height: 100vh; margin: 0; max-width: 100%; position: relative; z-index: 1; background: transparent;">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            {{-- Progress Indicator --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">Step 1 of 2</small>
                    <form id="skip-form" action="{{ route('onboarding.skip') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn px-5 py-2 shadow-sm" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%); border: none; color: white; border-radius: 10px; font-weight: 600;" onclick="event.preventDefault(); document.getElementById('skip-form').submit();">
                        Skip
                    </button>
                </div>
                <div class="progress" style="height: 8px; border-radius: 10px; background-color: rgba(255,255,255,0.5);">
                    <div class="progress-bar" role="progressbar" style="width: 50%; background: linear-gradient(90deg, #6f42c1 0%, #9b59b6 100%); border-radius: 10px;"></div>
                </div>
            </div>

            {{-- Main Card - with semi-transparent background --}}
            <div class="card border-0 shadow-lg" style="border-radius: 16px; position: relative; z-index: 2; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px);">
                <div class="card-body p-4 p-sm-5">
                    <h3 class="fw-bold mb-2" style="color: #1f0822;">What are you interested in?</h3>
                    <p class="text-muted mb-4">Select the industries you're looking for internships in. You can choose multiple.</p>

                    <form action="{{ route('onboarding.step2') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="course_of_study" class="form-label fw-bold" style="color: #1f0822;">Course of Study / Major</label>
                            <input type="text" class="form-control form-control-lg" id="course_of_study" name="course_of_study" 
                                   placeholder="e.g. Netcentric Computing, Information Technology" 
                                   value="{{ old('course_of_study', $preferences->course_of_study ?? '') }}"
                                   style="border-radius: 12px; border: 2px solid #e0d4f0; background: rgba(255,255,255,0.8);">
                            <div class="form-text">We'll use this to recommend internships that match your field.</div>
                        </div>

                        <h5 class="fw-bold mb-3" style="color: #1f0822;">Preferred Industries</h5>
                        
                        <div class="row g-3">
                            
                            @php
                            $industries = [
                                ['value' => 'IT/Information Technology', 'icon' => '💻', 'label' => 'IT / Tech'],
                                ['value' => 'Engineering', 'icon' => '🔧', 'label' => 'Engineering'],
                                ['value' => 'Business/Accounting/Finance', 'icon' => '💼', 'label' => 'Business'],
                                ['value' => 'Business/Accounting/Finance', 'icon' => '💰', 'label' => 'Finance'],
                                ['value' => 'Healthcare/Medical', 'icon' => '🏥', 'label' => 'Healthcare'],
                                ['value' => 'Creative/Design', 'icon' => '🎨', 'label' => 'Creative / Design'],
                                ['value' => 'Admin/Human Resource', 'icon' => '📊', 'label' => 'Admin / HR'],
                                ['value' => 'Build/Architecture/Construction', 'icon' => '🏗️', 'label' => 'Construction'],
                            ];
                            $selected = old('preferred_industries', $preferences->preferred_industries ?? []);
                            @endphp

                            @foreach($industries as $industry)
                            <div class="col-md-6 col-lg-4">
                                <input type="checkbox" class="btn-check" id="industry-{{ $loop->index }}" 
                                       name="preferred_industries[]" value="{{ $industry['value'] }}"
                                       {{ in_array($industry['value'], $selected) ? 'checked' : '' }}>
                                <label class="btn w-100 py-3 text-start industry-card" for="industry-{{ $loop->index }}" style="border: 2px solid #e0d4f0; border-radius: 12px; transition: all 0.3s; background: rgba(255,255,255,0.7);">
                                    <span class="fs-3 me-2">{{ $industry['icon'] }}</span>
                                    <span class="fw-semibold">{{ $industry['label'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('onboarding.welcome') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Back
                            </a>
                            <button type="submit" class="btn px-5 py-2 shadow-sm" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%); border: none; color: white; border-radius: 10px; font-weight: 600;">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="ms-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root{
        --violet-deep:  #4a2a80;
        --violet:       #6f42c1;
        --violet-soft:  #8b5cf6;
        --gold-deep:    #b9791f;
        --gold:         #d9a441;
        --gold-soft:    #f0c675;
        --teal-deep:    #1f7a6d;
        --teal:         #2f9e8f;
        --teal-soft:    #5fc9ba;
        --paper:        #faf8f5;
        --ink:          #2a2338;
    }

    /* Set body background with the pink color */
    body {
        background: #edddec !important;
        min-height: 100vh;
        margin: 0;
        overflow-x: hidden;
    }

    .industry-card {
        background: rgba(255, 255, 255, 0.7) !important;
        cursor: pointer;
        user-select: none;
        transition: all 0.3s ease;
    }
    .industry-card:hover {
        border-color: #9b59b6 !important;
        background: rgba(248, 244, 252, 0.9) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.15);
    }
    .btn-check:checked + .industry-card {
        border-color: #6f42c1 !important;
        background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%) !important;
        color: white;
    }

    .bg-scene {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        user-select: none;
        overflow: hidden;
    }

    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.35;
        pointer-events: none;
    }
    .blob--1 { 
        width: 420px; 
        height: 420px; 
        top: -8%; 
        left: -6%;
        background: radial-gradient(circle at 35% 35%, var(--violet-soft), transparent 70%); 
    }
    .blob--2 { 
        width: 380px; 
        height: 380px; 
        bottom: -10%; 
        right: -8%;
        background: radial-gradient(circle at 60% 60%, var(--gold-soft), transparent 70%); 
    }
    .blob--3 { 
        width: 300px; 
        height: 300px; 
        top: 38%; 
        left: 46%;
        background: radial-gradient(circle at 50% 50%, var(--teal-soft), transparent 72%); 
    }

    .bg-illustration {
        position: absolute;
        pointer-events: none;
    }

    @keyframes drift {
        0%, 100% { 
            transform: translate(0, 0) rotate(var(--r, 0deg)); 
        }
        50% { 
            transform: translate(6px, -10px) rotate(calc(var(--r, 0deg) + 2deg)); 
        }
    }
    
    .float { 
        animation: drift 14s ease-in-out infinite; 
        animation-delay: var(--d, 0s); 
    }

    @media (prefers-reduced-motion: reduce) {
        .float { 
            animation: none; 
        }
    }

    /* Container with transparent background */
    .container {
        position: relative;
        z-index: 1;
        background: transparent !important;
    }

    /* Card with glass effect */
    .card {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Form inputs with transparency */
    .form-control {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(5px);
    }

    /* Progress bar background */
    .progress {
        background-color: rgba(255, 255, 255, 0.5) !important;
    }
</style>
@endpush
@endsection