<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to InternStay - Select Your Role</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5edf7 0%, #edddec 50%, #e2d1eb 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

 :root{
    /* token system */
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

  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    min-height:100vh;
    background:
      radial-gradient(ellipse 90% 60% at 15% 0%, #f3edff 0%, transparent 60%),
      radial-gradient(ellipse 70% 50% at 100% 100%, #fff6e8 0%, transparent 55%),
      var(--paper);
    font-family: -apple-system, "Segoe UI", sans-serif;
    color: var(--ink);
    overflow-x:hidden;
  }


  .bg-scene{
    position:fixed;
    inset:0;
    z-index:0;
    pointer-events:none;
    user-select:none;
    overflow:hidden;
  }

  /* soft atmospheric color blobs, sit behind everything for depth */
  .blob{
    position:absolute;
    border-radius:50%;
    filter: blur(60px);
    opacity:.35;
  }
  .blob--1{ width:420px; height:420px; top:-8%; left:-6%;
    background: radial-gradient(circle at 35% 35%, var(--violet-soft), transparent 70%); }
  .blob--2{ width:380px; height:380px; bottom:-10%; right:-8%;
    background: radial-gradient(circle at 60% 60%, var(--gold-soft), transparent 70%); }
  .blob--3{ width:300px; height:300px; top:38%; left:46%;
    background: radial-gradient(circle at 50% 50%, var(--teal-soft), transparent 72%); }

  .bg-illustration{ position:absolute; }

  @keyframes drift{
    0%,100%{ transform: translate(0,0) rotate(var(--r,0deg)); }
    50%{ transform: translate(6px,-10px) rotate(calc(var(--r,0deg) + 2deg)); }
  }
  .float{ animation: drift 14s ease-in-out infinite; animation-delay: var(--d,0s); }

  @media (prefers-reduced-motion: reduce){
    .float{ animation:none; }
  }

        /* Sticky modern navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-brand-custom {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand-custom span {
            color: #6f42c1;
        }

        .nav-link-custom {
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            position: relative;
        }

        .nav-link-custom:hover {
            color: #6f42c1 !important;
        }

        .nav-link-custom::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #6f42c1;
            transition: width 0.2s ease;
        }

        .nav-link-custom:hover::after {
            width: 100%;
        }

        .navbar-brand-custom {
            color: #ffffff !important;
        }

        .navbar-brand-custom span {
            color: #c9a7ff; /* optional purple accent */
        }

        .nav-link-custom {
            color: #ffffff !important;
        }

        .nav-link-custom:hover {
            color: #c9a7ff !important;
        }

        /* Hero Section */
        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            color: #1f0822;
            letter-spacing: -1px;
            animation: fadeInDown 0.8s ease;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: #5d4263;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
            animation: fadeInUp 0.8s ease;
        }

        /* Main role container */
        .main-container {
            position: relative;
            z-index: 10;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Glassmorphic card design */
        .role-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(31, 8, 34, 0.05);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .role-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(111, 66, 193, 0.15);
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(111, 66, 193, 0.3);
        }

        /* Colored circular icon containers */
        .icon-wrapper {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            transition: all 0.3s ease;
        }

        .role-card:hover .icon-wrapper {
            transform: rotate(-8deg) scale(1.1);
        }

        .bg-student-icon {
            background-color: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }

        .bg-company-icon {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-admin-icon {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .card-title-custom {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: #1f0822;
            margin-bottom: 12px;
        }

        .card-text-custom {
            color: #5d4263;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        /* Premium Buttons */
        .btn-custom {
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            border: none;
        }

        .btn-student {
            background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.2);
        }

        .btn-student:hover {
            background: linear-gradient(135deg, #5e35b1 0%, #8e44ad 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.35);
        }

        .btn-company {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2);
        }

        .btn-company:hover {
            background: linear-gradient(135deg, #146c43 0%, #157347 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(25, 135, 84, 0.35);
        }

        .btn-admin {
            background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .btn-admin:hover {
            background: linear-gradient(135deg, #bd2130 0%, #fa5252 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.35);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer styling */
        footer {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 0.85rem;
            z-index: 10;
        }
    </style>
</head>
<body>

    {{-- Background Illustrations --}}
 <div class="bg-scene" aria-hidden="true">

  <!-- atmosphere -->
  <div class="blob blob--1"></div>
  <div class="blob blob--2"></div>
  <div class="blob blob--3"></div>

  <svg width="0" height="0" style="position:absolute">
    <defs>
      <linearGradient id="gViolet" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--violet-soft)"/>
        <stop offset="100%" stop-color="var(--violet-deep)"/>
      </linearGradient>
      <linearGradient id="gGold" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--gold-soft)"/>
        <stop offset="100%" stop-color="var(--gold-deep)"/>
      </linearGradient>
      <linearGradient id="gTeal" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--teal-soft)"/>
        <stop offset="100%" stop-color="var(--teal-deep)"/>
      </linearGradient>
      <linearGradient id="gRoute" x1="0" y1="0" x2="1" y2="0">
        <stop offset="0%"  stop-color="var(--violet)"  stop-opacity="0.5"/>
        <stop offset="50%" stop-color="var(--gold)"     stop-opacity="0.5"/>
        <stop offset="100%" stop-color="var(--teal)"    stop-opacity="0.5"/>
      </linearGradient>
    </defs>
  </svg>

  <!-- the signature element: one continuous journey line strung across the
       whole page, connecting every icon into a single narrative instead of
       scattering unrelated glyphs -->
  <svg class="bg-illustration" style="top:0; left:0; width:100%; height:100%; opacity:0.5;"
       viewBox="0 0 1440 1200" preserveAspectRatio="none" fill="none">
    <path d="M120 160
             C 300 60, 420 260, 560 210
             S 760 90, 900 220
             S 1040 480, 880 560
             S 560 620, 480 780
             S 620 980, 420 1040"
          stroke="url(#gRoute)" stroke-width="3.5" stroke-dasharray="2 14"
          stroke-linecap="round"/>
    <circle cx="120" cy="160"  r="6" fill="var(--violet)" opacity="0.55"/>
    <circle cx="900" cy="220"  r="6" fill="var(--gold)"   opacity="0.55"/>
    <circle cx="880" cy="560"  r="6" fill="var(--teal)"   opacity="0.55"/>
    <circle cx="420" cy="1040" r="6" fill="var(--violet)" opacity="0.55"/>
  </svg>

  <!-- Compass — journey start, top-left -->
  <svg class="bg-illustration float" style="width:130px; height:130px; top:9%; left:5%; --r:-8deg; --d:0s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(74,42,128,0.15));"
       viewBox="0 0 100 100" fill="url(#gViolet)">
    <path d="M50 5C25.1 5 5 25.1 5 50s20.1 45 45 45 45-20.1 45-45S74.9 5 50 5zm0 82c-20.4 0-37-16.6-37-37s16.6-37 37-37 37 16.6 37 37-16.6 37-37 37z"/>
    <path d="M50 25c-1.7 0-3 1.3-3 3v13.6L36.3 36.3c-1.2-1.2-3.1-1.2-4.2 0s-1.2 3.1 0 4.2L42.9 50l-9.2 9.2c-1.2 1.2-1.2 3.1 0 4.2.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9L50 56.4V70c0 1.7 1.3 3 3 3s3-1.3 3-3V56.4l10.8 10.8c.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9c1.2-1.2 1.2-3.1 0-4.2L57.1 50l9.2-9.2c1.2-1.2 1.2-3.1 0-4.2s-3.1-1.2-4.2 0L50 41.6V28c0-1.7-1.3-3-3-3z"/>
  </svg>

  <!-- Location pin — the destination being searched for -->
  <svg class="bg-illustration float" style="width:78px; height:78px; top:15%; left:36%; --r:6deg; --d:1.2s; opacity:0.6; filter: drop-shadow(0 6px 12px rgba(185,121,31,0.18));"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M50 2C31.2 2 16 17.2 16 36c0 23.3 30.6 59.9 31.9 61.5.6.7 1.5 1.1 2.4 1.1.9 0 1.8-.4 2.4-1.1C54.1 95.9 84 59.3 84 36 84 17.2 68.8 2 50 2zm0 50c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16z"/>
  </svg>

  <!-- Graduation cap — study leg of the journey -->
  <svg class="bg-illustration float" style="width:190px; height:190px; top:10%; right:6%; --r:-5deg; --d:2.1s; opacity:0.38; filter: drop-shadow(0 10px 20px rgba(31,122,109,0.15));"
       viewBox="0 0 100 100" fill="url(#gTeal)">
    <path d="M50 15L5 35l45 20 37-16.4V65c0 2.2 1.8 4 4 4s4-1.8 4-4V35.4L50 15zm0 53.6L18 54.4V62c0 8.8 14.3 16 32 16s32-7.2 32-16v-7.6L50 68.6z"/>
  </svg>

  <!-- Briefcase — career leg, mid right, larger anchor -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:13%; left:3%; --r:7deg; --d:0.6s; opacity:0.6; filter: drop-shadow(0 8px 18px rgba(74,42,128,0.16));"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M85 30H70v-8c0-4.4-3.6-8-8-8H38c-4.4 0-8 3.6-8 8v8H15c-5.5 0-10 4.5-10 10v40c0 5.5 4.5 10 10 10h70c5.5 0 10-4.5 10-10V40c0-5.5-4.5-10-10-10zM36 22c0-1.1.9-2 2-2h24c1.1 0 2 .9 2 2v8H36v-8zm53 58c0 2.2-1.8 4-4 4H15c-2.2 0-4-1.8-4-4V48h78v32zm0-38H11v-2c0-2.2 1.8-4 4-4h70c2.2 0 4 1.8 4 4v2z"/>
  </svg>

  <!-- small satellite compass, distant echo, bottom-left, faint for depth -->
  <svg class="bg-illustration float" style="width:75px; height:75px; top:44%; right:8%; --r:12deg; --d:1.8s; opacity:0.80;"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M50 2C23.5 2 2 23.5 2 50s21.5 48 48 48 48-21.5 48-48S76.5 2 50 2zm0 88c-22.1 0-40-17.9-40-40s17.9-40 40-40 40 17.9 40 40-17.9 40-40 40zm12-40L50 26 38 50l12 12 12-12z"/>
  </svg>

  <!-- House — settling down, bottom center-left, warm anchor -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:3%; left:33%; --r:-6deg; --d:0.9s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(185,121,31,0.18));"
       viewBox="0 0 24 24" fill="url(#gTeal)">
    <path d="M12 3L2 12h3v8h6v-5h2v5h6v-8h3L12 3z"/>
  </svg>

  <!-- Building — the city, bottom right, large but soft -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:2%; right:3%; --r:4deg; --d:1.5s; opacity:0.42;"
       viewBox="0 0 100 100" fill="url(#gViolet)">
    <path d="M40 2h45v96H5c2.2 0 4-1.8 4-4V35c0-2.2 1.8-4 4-4h27V2zm37 84V10H48v76h29zm-18-58h8v8h-8v-8zm0 20h8v8h-8v-8zm0 20h8v8h-8v-8zm-22-2h8v8h-8v-8zm0-20h8v8h-8v-8zm-20 40h8v8H17v-8zm0-20h8v8H17v-8z"/>
  </svg>

</div>

    {{-- Sticky modern navbar --}}
<nav class="navbar top-navbar navbar-expand-custom shadow-sm px-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('public.dashboard') }}" class="brand-wrapper text-decoration-none">
            <div class="monogram">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                    <!-- background -->
                    <defs>
                        <linearGradient id="bg" x1="0" x2="1">
                            <stop offset="0%" stop-color="#8d6cff"/>
                            <stop offset="100%" stop-color="#c8b3ff"/>
                        </linearGradient>
                    </defs>

                    <!-- Navigation Arrow -->
                    <path d="M47 8L17 28L30 31L33 48L47 8Z" fill="url(#bg)" />

                    <!-- Briefcase -->
                    <g transform="translate(5 35)">
                        <rect x="0" y="4" width="14" height="10" rx="2" fill="#cdb7ff"/>
                        <path d="M4 4V2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" stroke="#2a1235" stroke-width="1.5" fill="none"/>
                    </g>

                    <!-- House -->
                    <g transform="translate(34 37)">
                        <path d="M0 7L7 1L14 7V15H0V7Z" fill="#d9c8ff"/>
                        <rect x="5" y="9" width="3" height="6" rx="1" fill="#2a1235"/>
                    </g>
                </svg>
            </div>
            <div class="d-flex flex-column justify-content-center">
                <h5 class="brand-title mb-0">Intern<span>Stay</span></h5>
                <small class="text-white-50" style="font-size: 11px; letter-spacing: 0.3px;">Internship &amp; Rental</small>
            </div>
        </a>
    </div>

    <!-- Hamburger button for mobile/tablet -->
    <button class="navbar-toggler border-0 text-white p-2" type="button" data-bs-toggle="collapse" data-bs-target="#topbarNav" aria-controls="topbarNav" aria-expanded="false" aria-label="Toggle navigation" style="box-shadow: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>

    <!-- Collapsible container -->
    <div class="collapse navbar-collapse justify-content-between align-items-center" id="topbarNav">
        <!-- Navigation menu -->
        <div class="navbar-nav-container d-flex align-items-center gap-2 ms-lg-4 me-auto">
            <a href="{{ route('public.dashboard') }}" class="nav-link-custom active">Home</a>
        </div>

    </div>
</nav>

    {{-- Main Container --}}
    <div class="container main-container py-5">
        
        {{-- Hero Section --}}
        <div class="text-center mb-5">
            <h1 class="hero-title mb-3">Welcome to InternStay</h1>
            <p class="hero-subtitle">
                Your smart companion for internships and rental accommodation. <br class="d-none d-md-block">
                Select your role to continue.
            </p>
        </div>

        {{-- Role Cards --}}
        <div class="row g-4 justify-content-center">
            
            {{-- Card 1: Student --}}
            <div class="col-md-6 col-lg-4">
                <div class="role-card p-4 p-sm-5 text-center" onclick="window.location.href='{{ route('student.login') }}'">
                    <div>
                        <div class="icon-wrapper bg-student-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.786-.314a.5.5 0 0 0 .025-.917l-7.5-3.5z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.426 4.176 9.032z"/>
                            </svg>
                        </div>
                        <h4 class="card-title-custom">Student</h4>
                        <p class="card-text-custom">
                            Find internships, discover nearby rental accommodation, save favourites.
                        </p>
                    </div>
                    <button class="btn btn-custom btn-student">
                        Continue as Student
                    </button>
                </div>
            </div>

            {{-- Card 2: Company PIC --}}
            <div class="col-md-6 col-lg-4">
                <div class="role-card p-4 p-sm-5 text-center" onclick="window.location.href='{{ route('company.login') }}'">
                    <div>
                        <div class="icon-wrapper bg-company-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                            </svg>
                        </div>
                        <h4 class="card-title-custom">Company PIC</h4>
                        <p class="card-text-custom">
                            Post internship opportunities, manage listings, and connect with talented students.
                        </p>
                    </div>
                    <button class="btn btn-custom btn-company">
                        Continue as Company PIC
                    </button>
                </div>
            </div>

            {{-- Card 3: Admin --}}
            <div class="col-md-6 col-lg-4">
                <div class="role-card p-4 p-sm-5 text-center" onclick="window.location.href='{{ route('admin.login') }}'">
                    <div>
                        <div class="icon-wrapper bg-admin-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                            </svg>
                        </div>
                        <h4 class="card-title-custom">Admin</h4>
                        <p class="card-text-custom">
                            Manage users, verify companies, review internship postings, and oversee the platform.
                        </p>
                    </div>
                    <button class="btn btn-custom btn-admin">
                        Continue as Admin
                    </button>
                </div>
            </div>

        </div>

    </div>

    {{-- Footer Section --}}
    <footer class="text-center py-4 mt-5"
        style="background: linear-gradient(90deg, #1f0822 0%, #2d1235 100%); color: white;">
        <div class="container">
            <p class="mb-0">&copy; 2026 InternStay. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
