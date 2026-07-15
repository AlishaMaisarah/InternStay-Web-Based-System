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

        /* subtle pattern overlay */
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

        .container {
            background-color: transparent;
            position: relative;
            z-index: 1;
        }

        /* --- sidebar & layout --- */
        #wrapper {
            display: flex;
            width: 100%;
            transition: all 0.3s ease;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            /* enhanced gradient: 3 stops + faint radial glow */
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(180, 130, 220, 0.25) 0%, transparent 60%),
                linear-gradient(165deg, #1f0822 0%, #2d1235 50%, #1f1235 100%);
            box-shadow: 2px 0 20px rgba(0,0,0,0.25);
        }
        .sidebar-collapsed .sidebar {
            margin-left: -260px;
        }
        #content-area {
            flex-grow: 1;
            min-width: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* toggle button */
        .toggle-btn {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        @media (max-width: 992px) {
            #wrapper {
                display: block;
            }
            .sidebar {
                position: fixed !important;
                left: 0;
                top: 0;
                bottom: 0;
                height: 100vh;
                z-index: 1050 !important;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar-show .sidebar {
                transform: translateX(0);
            }
            .sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }
            #content-area {
                width: 100%;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1040 !important;
            }
            .sidebar-show .sidebar-overlay {
                display: block;
            }
        }

        /* --- sidebar brand & monogram --- */
        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .monogram {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.06);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .monogram svg {
            width: 42px;
            height: 42px;
        }
        .brand-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            letter-spacing: 0.5px;
            font-size: 26px;
            color: white;
            line-height: 1.2;
        }
        .brand-title span {
            color: #bba6e0;
        }

        /* --- sidebar nav --- */
        .sidebar-nav {
            flex: 1 1 auto;
        }
        .menu-eyebrow {
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
            margin-bottom: 12px;
            padding-left: 4px;
        }

        /* nav items with chip-style icons + left accent bar */
        .sidebar-link {
            border-radius: 10px;
            padding: 10px 14px !important;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.75);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            background: transparent;
            border: none;
            text-decoration: none;
            width: 100%;
        }
        .sidebar-link .icon-chip {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
            flex-shrink: 0;
        }
        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(3px);
        }
        .sidebar-link:hover .icon-chip {
            background: rgba(255,255,255,0.15);
        }
        .sidebar-link.active {
            background-color: rgba(255,255,255,0.07);
            color: white;
            font-weight: 600;
        }
        /* left accent bar (active) */
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 4px;
            background: #bba6e0;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 12px rgba(187, 166, 224, 0.4);
        }
        .sidebar-link.active .icon-chip {
            background: rgba(187, 166, 224, 0.25);
            color: #d9c9f0;
        }

        /* --- sidebar footer (logout) --- */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-footer .sidebar-link {
            padding: 8px 14px !important;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
        }
        .sidebar-footer .sidebar-link .icon-chip {
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.04);
        }
        .sidebar-footer .sidebar-link:hover {
            color: rgba(255,255,255,0.85);
            background-color: rgba(255, 0, 0, 0.1);
        }
        .sidebar-footer .sidebar-link:hover .icon-chip {
            background: rgba(255, 0, 0, 0.15);
        }
        .sidebar-footer .sidebar-link.logout-link:hover {
            color: #ff6b6b;
        }

        /* --- avatar --- */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: #e3f2fd;
            color: #2196f3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .avatar-link { display: inline-flex; text-decoration: none; }
        .avatar-link .avatar-circle { cursor: pointer; transition: transform .12s ease, box-shadow .12s ease; }
        .avatar-link:hover .avatar-circle { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,0.16); }

        /* --- top navbar: gradient continuation --- */
        .top-navbar {
            background: linear-gradient(135deg, #1f0822 0%, #2d1235 60%, #1f1235 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        /* --- cards, buttons (theme kept) --- */
        .card { border-radius: 16px !important; }
        .btn { border-radius: 12px !important; }
        .card.shadow-sm { box-shadow: 0 10px 30px rgba(31, 8, 34, 0.10) !important; }
        
        /* Purple button theme */
        .btn-primary {
            background-color: #6f42c1 !important;
            border-color: #6f42c1 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
            background-color: #5e35b1 !important;
            border-color: #5e35b1 !important;
        }

        .btn-success {
            background-color: #725483 !important;
            border-color: #725483 !important;
            color: #ffffff !important;
        }
        .btn-success:hover {
            background-color: #5f3f70 !important;
            border-color: #5f3f70 !important;
        }

        .btn-warning {
            background-color: #9c7ccf !important;
            border-color: #9c7ccf !important;
            color: #ffffff !important;
        }
        .btn-warning:hover {
            background-color: #725483 !important;
            border-color: #725483 !important;
        }

        .btn-info {
            background-color: #a594c8 !important;
            border-color: #a594c8 !important;
            color: #ffffff !important;
        }
        .btn-info:hover {
            background-color: #8e79b9 !important;
            border-color: #8e79b9 !important;
        }

        .btn-danger {
            background-color: #6a1b9a !important;
            border-color: #6a1b9a !important;
            color: #ffffff !important;
        }
        .btn-danger:hover {
            background-color: #4a148c !important;
            border-color: #4a148c !important;
        }

        .btn-outline-primary {
            color: #6f42c1 !important;
            border-color: #6f42c1 !important;
        }
        .btn-outline-primary:hover {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }

        .alert.custom-success {
            background-color: #725483 !important;
            color: #ffffff !important;
            border: none !important;
        }

        .badge-light {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .badge-light:hover {
            background-color: #6f42c1 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
        }

        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.1);
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(111, 66, 193, 0.15) !important;
        }

        /* ===== FIXED TABLE COLUMN WIDTHS & CELL PADDING ===== */
        @media (min-width: 992px) {
            .table-fixed {
                table-layout: fixed;
                width: 100%;
            }
            .table-fixed th,
            .table-fixed td {
                padding: 12px 16px !important;
                vertical-align: middle;
            }
            .col-no { width: 5%; }
            .col-name { width: 24%; }
            .col-company { width: 17%; }
            .col-industry { width: 14%; }
            .col-location { width: 13%; }
            .col-type { width: 12%; }
            .col-address { width: 11%; }
            .col-rent { width: 12%; }
            .col-status { width: 7%; }
            .col-source { width: 9%; }
            .col-action { width: 17%; }
            .table-fixed td:not(.col-action) {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        .text-white-50 { color: rgba(255,255,255,0.5) !important; }
    </style>
    <link href="/assets/css/custom-theme.css" rel="stylesheet">
</head>
<body>
    @include('layouts.partials.bg_illustrations')

<div id="wrapper" class="sidebar-collapsed">
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- ENHANCED SIDEBAR --}}
    <div class="sidebar p-4">
        <!-- brand + monogram -->
        <div class="mb-4 brand-wrapper">
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
                    <path
                        d="M47 8L17 28L30 31L33 48L47 8Z"
                        fill="url(#bg)"
                    />

                    <!-- Briefcase -->
                    <g transform="translate(5 35)">
                        <rect x="0" y="4" width="14" height="10"
                            rx="2"
                            fill="#cdb7ff"/>
                        <path d="M4 4V2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"
                            stroke="#2a1235"
                            stroke-width="1.5"
                            fill="none"/>
                    </g>

                    <!-- House -->
                    <g transform="translate(34 37)">
                        <path
                            d="M0 7L7 1L14 7V15H0V7Z"
                            fill="#d9c8ff"/>
                        <rect x="5" y="9" width="3" height="6"
                            rx="1"
                            fill="#2a1235"/>
                    </g>
                </svg>
            </div>
            <div>
                <h5 class="brand-title mb-0">Intern<span>Stay</span></h5>
                <small class="text-white-50" style="font-size: 11px; letter-spacing: 0.3px;">
                    @if(Auth::user()->role === 'admin')
                        Admin Panel
                    @else
                        Company Portal
                    @endif
                </small>
            </div>
        </div>

        <!-- nav with MENU eyebrow -->
        <div class="sidebar-nav">
            <div class="menu-eyebrow">Menu</div>
            <ul class="nav flex-column">
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item mb-2">
                        <a href="{{ route('home') }}" class="nav-link sidebar-link {{ request()->routeIs('home') || request()->routeIs('admin.dashboard') || request()->is('/') || request()->is('admin') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                </svg>
                            </span>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('internships.index') }}" class="nav-link sidebar-link {{ request()->routeIs('internships*') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                                </svg>
                            </span>
                            Internships
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('rentals.index') }}" class="nav-link sidebar-link {{ request()->routeIs('rentals*') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                            </span>
                            Rental Accommodation
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('users.index') }}" class="nav-link sidebar-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                </svg>
                            </span>
                            Users
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.verifications.index') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.verifications*') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.485 1.429a.3.3 0 0 0-.424 0L10 3.414 12.586 6l1.99-1.99a.3.3 0 0 0 0-.424L12.485 1.43zM1.5 13.5A1.5 1.5 0 0 0 3 15h10a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L1.5 6.85v6.65z"/>
                                </svg>
                            </span>
                            Company Verification
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.company-internships.index') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.company-internships*') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M4 11.5A.5.5 0 0 1 4.5 11h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7zM2.5 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-11z"/>
                                </svg>
                            </span>
                            Company Internships
                        </a>
                    </li>
                @else
                    <li class="nav-item mb-2">
                        <a href="{{ route('company.dashboard') }}" class="nav-link sidebar-link {{ request()->routeIs('company.dashboard') || request()->is('company/dashboard') ? 'active' : '' }}">
                            <span class="icon-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                </svg>
                            </span>
                            Dashboard
                        </a>
                    </li>

                    @if(Auth::user()->isApprovedCompany())
                        <li class="nav-item mb-2">
                            <a href="{{ route('company.internships.create') }}" class="nav-link sidebar-link {{ request()->routeIs('company.internships.create') ? 'active' : '' }}">
                                <span class="icon-chip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                </span>
                                Post Internship
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- MAIN CONTENT --}}
    <div id="content-area">

        {{-- TOP BAR (gradient continuation) --}}
        <nav class="navbar top-navbar shadow-sm px-4">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </button>
            </div>

            @auth
                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-link">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="fw-semibold text-white d-none d-sm-inline">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light border-0 opacity-75 hover-opacity-100 d-flex align-items-center justify-content-center" title="Logout" style="padding: 6px 10px; width: 34px; height: 34px; border-radius: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @endauth
        </nav>

        {{-- PAGE CONTENT --}}
        <div class="container mt-4">
            @yield('content')
        </div>

    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        const wrapper = document.getElementById('wrapper');
        wrapper.classList.toggle('sidebar-collapsed');
        wrapper.classList.toggle('sidebar-show');
        
        // Save state
        localStorage.setItem('admin-sidebar-collapsed', wrapper.classList.contains('sidebar-collapsed'));
    }

    // Restore state on load (desktop only)
    document.addEventListener('DOMContentLoaded', () => {
        const isCollapsed = localStorage.getItem('admin-sidebar-collapsed');
        if (isCollapsed === 'false' && window.innerWidth >= 992) {
            document.getElementById('wrapper').classList.remove('sidebar-collapsed');
        }
    });

    function checkPasswordStrength(password) {
        if (!password) {
            return { text: '', color: '', show: false };
        }
        const hasLetter = /[a-zA-Z]/.test(password);
        const hasDigit = /\d/.test(password);
        const hasSpecial = /[\W_]/.test(password);
        const isLengthValid = password.length >= 8;

        if (hasLetter && hasDigit && hasSpecial && isLengthValid) {
            return { text: 'Strong Password', color: '#198754', show: true };
        } else if ((hasLetter && hasDigit && password.length >= 6) || (hasLetter && hasSpecial && password.length >= 6) || (hasDigit && hasSpecial && password.length >= 6)) {
            return { text: 'Medium Password', color: '#fd7e14', show: true };
        } else {
            return { text: 'Weak Password', color: '#dc3545', show: true };
        }
    }

    function checkPasswordMatch(password, confirmPassword) {
        if (!confirmPassword) {
            return { text: '', color: '', show: false };
        }
        if (password === confirmPassword) {
            return { text: 'Password matched!', color: '#198754', show: true };
        } else {
            return { text: 'Passwords do not match.', color: '#dc3545', show: true };
        }
    }

    document.addEventListener('input', function(e) {
        if (e.target.name === 'password') {
            const passwordInput = e.target;
            const container = passwordInput.closest('.form-group, .mb-4, .mb-3');
            if (container) {
                let msgDiv = container.querySelector('.password-strength-msg');
                if (!msgDiv) {
                    msgDiv = document.createElement('div');
                    msgDiv.className = 'password-strength-msg small mt-1 fw-semibold';
                    const inputGroup = container.querySelector('.input-group') || passwordInput;
                    inputGroup.parentNode.insertBefore(msgDiv, inputGroup.nextSibling);
                }
                const strength = checkPasswordStrength(passwordInput.value);
                if (strength.show) {
                    msgDiv.textContent = strength.text;
                    msgDiv.style.color = strength.color;
                    msgDiv.style.display = 'block';
                } else {
                    msgDiv.style.display = 'none';
                }
            }
            
            // Also trigger match validation if confirm password has a value
            const form = passwordInput.closest('form');
            if (form) {
                const confirmInput = form.querySelector('input[name="password_confirmation"]');
                if (confirmInput) {
                    const confirmContainer = confirmInput.closest('.form-group, .mb-4, .mb-3');
                    if (confirmContainer) {
                        let confirmMsgDiv = confirmContainer.querySelector('.confirm-password-msg');
                        if (!confirmMsgDiv) {
                            confirmMsgDiv = document.createElement('div');
                            confirmMsgDiv.className = 'confirm-password-msg small mt-1 fw-semibold';
                            const confirmInputGroup = confirmContainer.querySelector('.input-group') || confirmInput;
                            confirmInputGroup.parentNode.insertBefore(confirmMsgDiv, confirmInputGroup.nextSibling);
                        }
                        const match = checkPasswordMatch(passwordInput.value, confirmInput.value);
                        if (match.show) {
                            confirmMsgDiv.textContent = match.text;
                            confirmMsgDiv.style.color = match.color;
                            confirmMsgDiv.style.display = 'block';
                        } else {
                            confirmMsgDiv.style.display = 'none';
                        }
                    }
                }
            }
        }

        if (e.target.name === 'password_confirmation') {
            const confirmInput = e.target;
            const form = confirmInput.closest('form');
            if (form) {
                const passwordInput = form.querySelector('input[name="password"]');
                if (passwordInput) {
                    const confirmContainer = confirmInput.closest('.form-group, .mb-4, .mb-3');
                    if (confirmContainer) {
                        let confirmMsgDiv = confirmContainer.querySelector('.confirm-password-msg');
                        if (!confirmMsgDiv) {
                            confirmMsgDiv = document.createElement('div');
                            confirmMsgDiv.className = 'confirm-password-msg small mt-1 fw-semibold';
                            const confirmInputGroup = confirmContainer.querySelector('.input-group') || confirmInput;
                            confirmInputGroup.parentNode.insertBefore(confirmMsgDiv, confirmInputGroup.nextSibling);
                        }
                        const match = checkPasswordMatch(passwordInput.value, confirmInput.value);
                        if (match.show) {
                            confirmMsgDiv.textContent = match.text;
                            confirmMsgDiv.style.color = match.color;
                            confirmMsgDiv.style.display = 'block';
                        } else {
                            confirmMsgDiv.style.display = 'none';
                        }
                    }
                }
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const btn = e.target.closest('.toggle-password');
            const input = btn.previousElementSibling;
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.8 8.8 0 0 0-2.79.488l.77.77A7.7 7.7 0 0 1 8 4c4.09 0 7.3 3.6 8 4-.37.49-1.27 1.98-2.63 3.235l.79.79zM10.95 9.9 8 6.95 6.95 8 9.9 10.95zm-4.02-.38a4.9 4.9 0 0 0-.25-.77L4.146 6.155A4.8 4.8 0 0 0 4 7a4 4 0 0 0 4 4 4.8 4.8 0 0 0 .845-.076L7.778 9.873A5 5 0 0 1 6.93 9.52M3.05 3.05a.5.5 0 0 0-.707.707l1.36 1.36C1.98 6.55 1 8 1 8s3 5.5 8 5.5a8.8 8.8 0 0 0 2.63-.445l.98.98a.5.5 0 0 0 .708-.707l-9-9z"/>
                            <path d="M11.612 9.564 11.25 9.2A1.88 1.88 0 0 0 9.5 7.5L9.12 7.129 8.93 6.94 6.94 8.93l.189.191 3.568 3.568A2 2 0 0 0 11.612 9.564M7.496 8.01 4.5 5c-.01.12-.01.24-.01.36A3.5 3.5 0 0 0 8 8.86c.12 0 .24 0 .36-.01L8.01 8.36a2 2 0 0 1-.514-.35z"/>
                        </svg>
                    `;
                } else {
                    input.type = 'password';
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4s3.88.668 5.168 1.957A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12s-3.879-.668-5.168-1.957A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                        </svg>
                    `;
                }
            }
        }
    });
</script>
@yield('modals')
</body>
</html>