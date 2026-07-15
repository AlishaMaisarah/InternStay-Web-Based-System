<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InternStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ----- global headings (Poppins) ----- */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f0ff 0%, #f0e6ff 40%, #faf0f7 100%);
            position: relative;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        /* subtle pattern overlay (kept from original) */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(111, 66, 193, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(149, 117, 205, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0; 
        }

        /* Navbar Styles */
        .navbar.top-navbar {
            background: linear-gradient(135deg, #2a1235 0%, #4a2a6b 50%, #6f42c1 100%);
            padding: 0.75rem 1.5rem;
            min-height: 70px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #c9a7ff !important;
        }

        .navbar .dropdown-toggle {
            color: white !important;
        }

        .navbar-brand {
            color: white !important;
        }

        .dropdown-toggle-split::after {
            margin-left: 0;
        }

        .nav-item .dropdown {
            position: relative;
        }

        /* Brand Styles */
        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .monogram {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
        }

        .monogram svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .brand-title {
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            line-height: 1.2;
            letter-spacing: -0.3px;
        }

        .brand-title span {
            color: #c9a7ff;
        }

        /* Navigation Links */
        .nav-link-custom {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link-custom:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link-custom.active {
            color: white;
            background: rgba(255, 255, 255, 0.12);
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            border-radius: 4px;
            background: linear-gradient(90deg, #c9a7ff, #8d6cff);
        }

        .nav-item .dropdown .dropdown-menu {
            background: linear-gradient(135deg, #2a1235 0%, #4a2a6b 50%, #6f42c1 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            padding: 0.5rem !important;
            min-width: 220px !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4) !important;
            margin-top: 8px !important;
            backdrop-filter: blur(10px);

            left: 0 !important;
            right: auto !important;
            transform: translateX(0) !important;
        }

        .nav-item .dropdown .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.65rem 1rem !important;
            border-radius: 8px !important;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item .dropdown .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            color: white !important;
            transform: translateX(4px);
        }

        .nav-item .dropdown .dropdown-item.active {
            background: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
        }

        .nav-item .dropdown .dropdown-item i {
            color: #c9a7ff;
            font-size: 0.95rem;
        }

        .nav-item .dropdown .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.08) !important;
            margin: 0.3rem 0 !important;
        }

        /* Avatar */
        .avatar-circle {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #8d6cff, #c9a7ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .avatar-circle:hover {
            transform: scale(1.08);
            border-color: white;
        }

        /* Button Styles */
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .btn-light {
            background: white;
            color: #4a2a6b;
        }

        .btn-light:hover {
            background: #f0e6ff;
            color: #4a2a6b;
        }

        /* Navbar Toggler */
        .navbar-toggler {
            border: none;
            color: white;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        @media (max-width: 576px) {
            .brand-title {
                font-size: 1.1rem;
            }

            .monogram {
                width: 36px;
                height: 36px;
            }

            .navbar.top-navbar {
                padding: 0.5rem 0.75rem;
            }
        }

        /* Dropdown animation */
        .nav-item .dropdown .dropdown-menu {
            animation: slideDown 0.25s ease forwards;
            transform-origin: top center;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* --- cards, buttons (theme kept) --- */
        .card { border-radius: 16px !important; }
        .btn { border-radius: 12px !important; }
        .card.shadow-sm { box-shadow: 0 10px 30px rgba(31, 8, 34, 0.10) !important; }
        .btn-primary { background-color: #6f42c1; border-color: #6f42c1; }
        .btn-primary:hover, .btn-primary:active, .btn-primary:focus { background-color: #5e35b1; border-color: #5e35b1; }
        .btn-secondary { background-color: #9575cd; border-color: #9575cd; }
        .btn-secondary:hover, .btn-secondary:active, .btn-secondary:focus { background-color: #7e57c2; border-color: #7e57c2; }
        .btn-outline-primary { color: #6f42c1; border-color: #6f42c1; }
        .btn-outline-primary:hover { background-color: #6f42c1; color: #fff; }
        .btn-info { background-color: #d1c4e9; border-color: #d1c4e9; color: #311b92; }
        .btn-info:hover { background-color: #b39ddb; border-color: #b39ddb; color: #311b92; }
        .hover-lift { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(111, 66, 193, 0.15) !important; }
        .bg-primary-soft { background-color: rgba(111, 66, 193, 0.1); }
        .company-icon { transition: all 0.3s ease; }
        .hover-lift:hover .company-icon { transform: scale(1.05) rotate(-3deg); }
        .form-control:focus { border-color: #6f42c1; box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.1); }
        .badge-light { transition: all 0.2s ease; cursor: pointer; }
        .badge-light:hover { background-color: #6f42c1 !important; color: white !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3); }

        /* misc */
        .text-white-50 { color: rgba(255,255,255,0.5) !important; }

        /* ----- Mobile Overlay Redesign (< 992px) ----- */
        @media (max-width: 991.98px) {
            /* Full-screen menu overlay */
            #topbarNav {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                z-index: 1050 !important;
                background: linear-gradient(135deg, #1f0822 0%, #2d1235 50%, #1f1235 100%) !important;
                padding: 30px 24px !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                align-items: center !important;
                overflow-y: auto !important;
                box-sizing: border-box !important;
                
                /* Animation transitions */
                opacity: 0 !important;
                transform: translateY(-50px) !important;
                visibility: hidden !important;
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), opacity 0.4s ease, visibility 0.4s !important;
            }

            #topbarNav.show {
                display: flex !important;
                opacity: 1 !important;
                transform: translateY(0) !important;
                visibility: visible !important;
            }
            
            #topbarNav.collapsing {
                display: flex !important;
                height: 100vh !important;
                opacity: 0 !important;
                transform: translateY(-50px) !important;
                visibility: visible !important;
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), opacity 0.4s ease !important;
            }

            body.mobile-menu-open {
                overflow: hidden !important;
            }

            /* Container of main menu items */
            .navbar-nav-container {
                flex-direction: column !important;
                width: 100% !important;
                gap: 16px !important;
                margin: 20px 0 !important;
                padding: 0 !important;
            }

            /* Main navigation items (cards) */
            .navbar-nav-container .nav-link-custom:not(.dropdown-toggle) {
                width: 100% !important;
                background: rgba(255, 255, 255, 0.08) !important;
                border: 1px solid rgba(255, 255, 255, 0.12) !important;
                border-radius: 16px !important;
                padding: 14px 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                min-height: 48px !important;
                color: white !important;
                font-size: 16px !important;
                transition: all 0.25s ease !important;
                box-shadow: none !important;
            }

            .navbar-nav-container .nav-link-custom:not(.dropdown-toggle):hover,
            .navbar-nav-container .nav-link-custom:not(.dropdown-toggle).active {
                background: rgba(255, 255, 255, 0.15) !important;
                border-color: rgba(255, 255, 255, 0.25) !important;
                box-shadow: 0 8px 24px rgba(111, 66, 193, 0.2) !important;
            }

            /* Dropdown navigation item card */
            .nav-item {
                width: 100% !important;
            }

            .nav-item .dropdown {
                flex-direction: row !important;
                flex-wrap: wrap !important;
                width: 100% !important;
                background: rgba(255, 255, 255, 0.08) !important;
                border: 1px solid rgba(255, 255, 255, 0.12) !important;
                border-radius: 16px !important;
                overflow: hidden !important;
                align-items: stretch !important;
            }

            .nav-item .dropdown .nav-link-custom:not(.dropdown-toggle) {
                flex: 1 !important;
                background: transparent !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }

            .nav-item .dropdown .dropdown-toggle-split {
                width: 50px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-left: 1px solid rgba(255, 255, 255, 0.12) !important;
                padding: 14px 0 !important;
                color: white !important;
                background: transparent !important;
                border-radius: 0 !important;
            }

            .nav-item .dropdown .dropdown-menu {
                position: relative !important;
                top: 0 !important;
                left: 0 !important;
                transform: none !important;
                width: 100% !important;
                background: rgba(0, 0, 0, 0.15) !important;
                border: none !important;
                border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
                border-radius: 0 !important;
                padding: 8px 12px !important;
                box-shadow: none !important;
                margin: 0 !important;
                backdrop-filter: none !important;
            }

            .nav-item .dropdown .dropdown-item {
                width: 100% !important;
                padding: 12px 16px !important;
                border-radius: 12px !important;
                color: rgba(255, 255, 255, 0.9) !important;
                font-size: 15px !important;
                background: transparent !important;
                min-height: 48px !important;
                display: flex !important;
                align-items: center !important;
            }

            .nav-item .dropdown .dropdown-item:hover,
            .nav-item .dropdown .dropdown-item.active {
                background: rgba(255, 255, 255, 0.12) !important;
                color: white !important;
                transform: none !important;
            }

            /* Close Button Style */
            .btn-close-mobile {
                transition: all 0.2s ease;
                opacity: 0.8;
            }
            .btn-close-mobile:hover {
                transform: scale(1.1);
                opacity: 1;
            }

            /* Auth buttons / User actions section */
            .auth-buttons-container {
                flex-direction: column !important;
                width: 100% !important;
                gap: 16px !important;
                margin-top: 20px !important;
                padding: 24px 0 0 0 !important;
                border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
                align-items: stretch !important;
            }

            /* Guest authentication buttons */
            .auth-buttons-container .btn {
                width: 100% !important;
                min-height: 48px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 16px !important;
                padding: 12px 20px !important;
                border-radius: 16px !important;
                margin: 0 !important;
            }

            .auth-buttons-container .btn-outline-light {
                background: rgba(255, 255, 255, 0.08) !important;
                border: 1px solid rgba(255, 255, 255, 0.25) !important;
                color: white !important;
            }

            .auth-buttons-container .btn-light {
                background: white !important;
                color: #2d1235 !important;
                border: none !important;
                font-weight: 600 !important;
            }

            /* Authenticated user Profile card */
            .auth-buttons-container .auth-user-wrapper {
                flex-direction: column !important;
                width: 100% !important;
                gap: 16px !important;
                align-items: stretch !important;
            }

            .auth-buttons-container .profile-card-link {
                width: 100% !important;
                background: rgba(255, 255, 255, 0.08) !important;
                border: 1px solid rgba(255, 255, 255, 0.12) !important;
                border-radius: 16px !important;
                padding: 12px 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                min-height: 48px !important;
                text-decoration: none !important;
                transition: all 0.25s ease !important;
            }

            .auth-buttons-container .profile-card-link:hover {
                background: rgba(255, 255, 255, 0.15) !important;
                border-color: rgba(255, 255, 255, 0.25) !important;
            }

            .auth-buttons-container .profile-card-link .avatar-circle {
                width: 36px !important;
                height: 36px !important;
                margin-right: 12px !important;
                border-radius: 50% !important;
            }

            .auth-buttons-container .profile-card-link .profile-name {
                display: inline-block !important;
                color: white !important;
                font-size: 16px !important;
                font-weight: 600 !important;
            }

            /* Authenticated user Logout button */
            .auth-buttons-container form {
                width: 100% !important;
            }

            .auth-buttons-container form button {
                width: 100% !important;
                min-height: 48px !important;
                padding: 12px 20px !important;
                background: rgba(220, 53, 69, 0.12) !important;
                border: 1px solid rgba(220, 53, 69, 0.25) !important;
                border-radius: 16px !important;
                color: #ff8585 !important;
                font-size: 16px !important;
                font-weight: 600 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                transition: all 0.25s ease !important;
            }

            .auth-buttons-container form button:hover {
                background: rgba(220, 53, 69, 0.2) !important;
                border-color: rgba(220, 53, 69, 0.4) !important;
                color: white !important;
            }
            
            .auth-buttons-container form button svg {
                width: 20px !important;
                height: 20px !important;
                margin-right: 8px !important;
            }
        }

        /* ----- Footer Redesign Styles ----- */
        .site-footer {
            background: radial-gradient(ellipse at 20% 30%, rgba(180, 130, 220, 0.15) 0%, transparent 60%),
                        linear-gradient(165deg, #1f0822 0%, #2d1235 50%, #1f1235 100%) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            font-size: 0.95rem;
            line-height: 1.6;
            width: 100%;
        }

        .site-footer h5, 
        .site-footer h6 {
            color: white;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .site-footer .text-muted-light {
            color: rgba(255, 255, 255, 0.7);
        }

        .site-footer a {
            color: rgba(255, 255, 255, 0.7) !important;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .site-footer a:hover {
            color: #c9a7ff !important;
            padding-left: 4px;
        }

        .footer-social-icons {
            display: flex;
            gap: 12px;
        }

        .footer-social-icons a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            transition: all 0.3s ease;
        }

        .footer-social-icons a:hover {
            background: #6f42c1 !important;
            border-color: #c9a7ff !important;
            color: white !important;
            transform: translateY(-2px);
            padding-left: 0 !important; /* Reset padding hover effect */
        }

        /* Tablet (768px - 991px) styling */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .site-footer .row {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
            }
            .site-footer .footer-section-brand,
            .site-footer .footer-section-nav,
            .site-footer .footer-section-resources {
                flex: 1 1 33.333% !important;
                width: 33.333% !important;
                padding: 0 15px !important;
            }
            .site-footer .footer-section-brand {
                margin-bottom: 0 !important;
            }
        }

        /* Mobile (< 768px) styling */
        @media (max-width: 767.98px) {
            .site-footer {
                background: linear-gradient(165deg, #1f0822 0%, #2d1235 100%) !important;
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            .site-footer .container {
                display: flex;
                flex-direction: column;
                gap: 16px;
                padding-left: 16px;
                padding-right: 16px;
            }

            .site-footer .row {
                display: flex;
                flex-direction: column;
                gap: 16px;
                margin: 0 !important;
            }

            .site-footer .row > div {
                width: 100% !important;
                background: rgba(255, 255, 255, 0.04) !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                border-radius: 20px !important;
                padding: 24px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
                box-sizing: border-box !important;
            }

            .footer-section-brand {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .footer-section-brand .brand-wrapper {
                justify-content: center;
            }

            .footer-section-brand p {
                max-width: 320px;
                margin-top: 12px;
            }

            .site-footer h6 {
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                padding-bottom: 12px;
                margin-bottom: 16px !important;
                text-align: center;
            }

            .site-footer ul {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
                padding-left: 0;
            }

            .site-footer ul li {
                width: 100%;
                text-align: center;
            }

            .site-footer ul li a {
                display: block;
                width: 100%;
                padding: 8px 0;
                min-height: 48px;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                box-sizing: border-box !important;
            }

            .site-footer ul li a:hover {
                background: rgba(255, 255, 255, 0.08);
                padding-left: 0 !important;
            }

            .footer-bottom {
                margin-top: 0 !important;
                border-top: none !important;
                background: rgba(255, 255, 255, 0.04) !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                border-radius: 20px !important;
                padding: 20px !important;
                text-align: center;
                flex-direction: column !important;
            }

            .footer-bottom p {
                order: 2;
                margin-top: 8px;
            }

            .footer-bottom .footer-social-icons {
                order: 1;
                justify-content: center;
                width: 100%;
            }
        }
    </style>
    @yield('header_styles')
    <link href="/assets/css/custom-theme.css" rel="stylesheet">
</head>
<body>
    @include('layouts.partials.bg_illustrations')

<div id="wrapper">
    {{-- MAIN CONTENT --}}
    <div id="content-area">
        {{-- TOP BAR --}}
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
                <!-- Mobile overlay header (only visible on mobile/tablet) -->
                <div class="mobile-overlay-header d-lg-none d-flex justify-content-between align-items-center w-100 mb-4">
                    <div class="brand-wrapper text-decoration-none">
                        <div class="monogram">
                            <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <!-- background -->
                                <defs>
                                    <linearGradient id="bg-mobile" x1="0" x2="1">
                                        <stop offset="0%" stop-color="#8d6cff"/>
                                        <stop offset="100%" stop-color="#c8b3ff"/>
                                    </linearGradient>
                                </defs>
                                <path d="M47 8L17 28L30 31L33 48L47 8Z" fill="url(#bg-mobile)" />
                                <g transform="translate(5 35)">
                                    <rect x="0" y="4" width="14" height="10" rx="2" fill="#cdb7ff"/>
                                    <path d="M4 4V2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" stroke="#2a1235" stroke-width="1.5" fill="none"/>
                                </g>
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
                    </div>
                    <button class="btn-close-mobile border-0 bg-transparent text-white p-2" type="button" data-bs-toggle="collapse" data-bs-target="#topbarNav" aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Navigation links -->
                <div class="navbar-nav-container d-flex align-items-center gap-2 ms-lg-4 me-auto">

                    <a href="{{ route('public.dashboard') }}"
                    class="nav-link-custom {{ request()->routeIs('public.dashboard') ? 'active' : '' }}">
                        Home
                    </a>

                    <div class="nav-item">

                        <div class="dropdown d-flex align-items-center">

                            <!-- Main clickable link -->
                            <a href="{{ route('public.internships.index') }}"
                            class="nav-link-custom {{ request()->routeIs('public.internships*') ? 'active' : '' }}">
                                Internships
                            </a>

                            <!-- Dropdown arrow -->
                            <a class="nav-link-custom dropdown-toggle dropdown-toggle-split px-2"
                            href="#"
                            id="internshipDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            data-bs-display="static"
                            aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </a>

                            <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="internshipDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('public.rentals*') ? 'active' : '' }}"
                                    href="{{ route('public.rentals.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                        </svg>
                                        Rental Accommodations
                                    </a>
                                </li>
                            </ul>

                        </div>

                    </div>

                    @auth
                        <a href="{{ route('favorites.index') }}"
                        class="nav-link-custom {{ request()->routeIs('favorites*') ? 'active' : '' }}">
                            Favourites
                        </a>
                    @endauth

                </div>
     
                <div class="auth-buttons-container d-flex gap-2 align-items-center">
                    @guest
                        <!--<a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">Sign Up</a>-->
                        <a href="{{ route('login') }}" class="btn btn-light btn-sm">Sign Up / Login</a>
                    @endguest 
     
                    @auth
                        <div class="d-flex align-items-center gap-3 auth-user-wrapper">
                            <a href="{{ route('user.profile') }}" class="profile-card-link d-flex align-items-center gap-3 text-white text-decoration-none" title="My Profile">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="profile-name fw-semibold d-none d-sm-inline-block">{{ Auth::user()->name }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light border-0 opacity-75 hover-opacity-100 d-flex align-items-center justify-content-center" title="Logout" style="padding: 6px 10px; width: 34px; height: 34px; border-radius: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                    </svg>
                                    <span class="ms-2 d-lg-none">Logout</span>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- PAGE CONTENT --}}
        <div class="container my-4">
            @yield('content')
        </div>

        {{-- FOOTER --}}
        <footer class="site-footer text-white py-5">
            <div class="container">
                <div class="row gy-4 gy-md-0 align-items-start">
                    <!-- Column 1: Brand -->
                    <div class="col-lg-5 col-md-5 footer-section-brand">
                        <a href="{{ route('public.dashboard') }}" class="brand-wrapper text-decoration-none d-inline-flex align-items-center mb-3">
                            <div class="monogram">
                                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="bg-footer" x1="0" x2="1">
                                            <stop offset="0%" stop-color="#8d6cff"/>
                                            <stop offset="100%" stop-color="#c8b3ff"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M47 8L17 28L30 31L33 48L47 8Z" fill="url(#bg-footer)" />
                                    <g transform="translate(5 35)">
                                        <rect x="0" y="4" width="14" height="10" rx="2" fill="#cdb7ff"/>
                                        <path d="M4 4V2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" stroke="#2a1235" stroke-width="1.5" fill="none"/>
                                    </g>
                                    <g transform="translate(34 37)">
                                        <path d="M0 7L7 1L14 7V15H0V7Z" fill="#d9c8ff"/>
                                        <rect x="5" y="9" width="3" height="6" rx="1" fill="#2a1235"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="d-flex flex-column justify-content-center text-start ms-2">
                                <h5 class="brand-title mb-0">Intern<span>Stay</span></h5>
                                <small class="text-white-50" style="font-size: 11px; letter-spacing: 0.3px;">Compass</small>
                            </div>
                        </a>
                        <p class="text-muted-light small mt-2 mb-0">
                            <a>Discover internships and nearby rental accommodations</a> 
                            <br>
                            <a>through smart recommendations and interactive maps.</a>
                        </p>
                    </div>

                    <!-- Column 2: Navigation -->
                    <div class="col-lg-3 col-md-3 footer-section-nav">
                        <h6 class="text-white fw-bold mb-3">Navigation</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="{{ route('public.dashboard') }}" class="small text-decoration-none">Home</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.internships.index') }}" class="small text-decoration-none">Internship Listings</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.rentals.index') }}" class="small text-decoration-none">Rental Accommodations</a>
                            </li>
                            @auth
                            <li class="mb-2">
                                <a href="{{ route('favorites.index') }}" class="small text-decoration-none">Favourites</a>
                            </li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Column 3: Resources -->
                    <div class="col-lg-4 col-md-4 footer-section-resources text-md-end">
                        <h6 class="text-white fw-bold mb-3">Resources</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="#" class="small text-decoration-none">About Us</a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="small text-decoration-none">Privacy Policy</a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="small text-decoration-none">Terms & Conditions</a>
                            </li>
                            <li class="mb-2">
                                <a href="mailto:hello@internstay.com" class="small d-inline-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                    hello@internstay.com
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Bottom Row -->
                <div class="footer-bottom mt-5 pt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <p class="mb-0 text-white-50 small">&copy; 2026 InternStay. All Rights Reserved.</p>

                </div>
            </div>
        </footer>

    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topbarNav = document.getElementById('topbarNav');
        if (topbarNav) {
            topbarNav.addEventListener('show.bs.collapse', function() {
                document.body.classList.add('mobile-menu-open');
            });
            topbarNav.addEventListener('hidden.bs.collapse', function() {
                document.body.classList.remove('mobile-menu-open');
            });
        }
    });
</script>

@yield('modals')
@stack('scripts')
</body>
</html>