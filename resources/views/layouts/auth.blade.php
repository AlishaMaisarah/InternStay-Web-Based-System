<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InternStay - Authentication</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- body should never have shadow box --}}
    <style> 
        body {
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        background-color: #edddec;
        /*background:
        linear-gradient(
        135deg,
        #f8efff 0%,
        #f5f0ff 50%,
        #fff7fd 100%
        );*/
        overflow-x: hidden;
    }

    body::before {
        content: "";
        position: fixed;
        width: 500px;
        height: 500px;
        background: rgba(111, 66, 193, 0.08);
        border-radius: 50%;
        top: -150px;
        left: -150px;
        filter: blur(60px);
        z-index: -1;
    }

    .brand-logo {
        font-family: 'Poppins', sans-serif;
    }

    .card {
        border: none;
        border-radius: 24px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        animation: fadeUp 0.5s ease;
    }

    .btn,
    .form-control,
    .card {
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .btn-primary:hover {
        background-color: #5e35b1;
        border-color: #5e35b1;
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(111, 66, 193, 0.25);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
    }

    .form-control,
    .input-group-text {
        padding: 0.75rem 1rem;
    }

    .input-group-text {
        background-color: #fff;
        border-color: #dee2e6;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    </style>
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.partials.bg_illustrations')

{{-- Page content --}}
<main>
    @yield('content')
</main>

{{-- Bootstrap JS --}}
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
