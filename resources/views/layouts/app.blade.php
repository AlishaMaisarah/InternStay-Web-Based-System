<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
</head>

<style>
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

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .navbar.top-navbar {
            padding: 0.75rem 1rem;
        }

        .navbar-nav-container {
            flex-direction: column;
            align-items: flex-start !important;
            width: 100%;
            padding: 1rem 0;
        }

        .nav-link-custom {
            width: 100%;
            padding: 0.6rem 0.75rem;
        }

        .nav-item {
            width: 100%;
            flex-wrap: wrap;
        }

        .auth-buttons-container {
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            width: 100%;
            justify-content: center;
        }

        .nav-link-custom.active::after {
            display: none;
        }
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

</style>

<body>
    @include('layouts.partials.bg_illustrations')
    <div id="app">
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
                <!-- Navigation links -->
                <div class="navbar-nav-container d-flex align-items-center gap-2 ms-lg-4 me-auto">

                    <a href="{{ route('public.dashboard') }}"
                    class="nav-link-custom {{ request()->routeIs('public.dashboard') ? 'active' : '' }}">
                        Home
                    </a>

                </div>

            </div>
        </nav>
    </div>
        

        <main>
            @yield('content')
        </main>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
</body>
</html>