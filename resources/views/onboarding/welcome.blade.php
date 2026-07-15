@extends('layouts.app')

@section('content')
{{-- Background Illustrations --}}
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

    <!-- Welcome/Star icon (new for this page) -->
    <svg class="bg-illustration float" style="position:absolute; width:100px; height:100px; top:35%; left:55%; --r:5deg; --d:0.4s; opacity:0.5; filter: drop-shadow(0 8px 16px rgba(185,121,31,0.15)); pointer-events:none;"
         viewBox="0 0 100 100" fill="url(#gGold)">
        <path d="M50 2L61 37h37L71 57l12 35-33-21-33 21 12-35L2 37h37L50 2z"/>
    </svg>
</div>

{{-- MAIN CONTENT - with transparent background --}}
<div class="container" style="min-height: 100vh; margin: 0; max-width: 100%; padding-top: 2rem; position: relative; z-index: 1; background: transparent;">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-8 col-lg-6">
            <div class="text-center mb-5">
                <div class="mb-4">
                    <div class="bg-gradient rounded-circle d-inline-flex p-4 mb-3" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%); box-shadow: 0 8px 32px rgba(111, 66, 193, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="white" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="fw-bold mb-3" style="color: #1f0822; font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.05);">Welcome to InternStay! 🎓</h1>
                <p class="text-muted fs-5 mb-2" style="color: rgba(0,0,0,0.7) !important;">Let's personalize your experience in just a few quick steps.</p>
                <p class="text-muted" style="color: rgba(0,0,0,0.6) !important;">This will only take about 2 minutes and help us show you the most relevant internships and accommodations.</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 16px; position: relative; z-index: 2; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                <div class="card-body p-4 p-sm-5">
                    <div class="d-grid gap-3">
                        <a href="{{ route('onboarding.step1') }}" class="btn btn-lg py-3 shadow-sm" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%); border: none; color: white; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Get Started
                        </a>
                        
                        <form action="{{ route('onboarding.skip') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted w-100" style="text-decoration: none; color: rgba(0,0,0,0.5) !important; transition: all 0.3s ease;">
                                Skip for now
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 text-center">
                        <small class="text-muted" style="color: rgba(0,0,0,0.5) !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                            You can always update your preferences later from your profile
                        </small>
                    </div>
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

    /* Button hover effect */
    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(111, 66, 193, 0.3) !important;
    }

    .btn-link:hover {
        color: #6f42c1 !important;
        text-decoration: underline !important;
    }

    /* Text colors on pink background */
    .text-muted {
        color: rgba(0, 0, 0, 0.6) !important;
    }

    /* Logo glow */
    .bg-gradient {
        box-shadow: 0 8px 32px rgba(111, 66, 193, 0.3);
        transition: all 0.3s ease;
    }

    .bg-gradient:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 48px rgba(111, 66, 193, 0.4);
    }

    h1 {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush
@endsection