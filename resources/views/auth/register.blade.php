@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="min-vh-100 d-flex align-items-center py-5">
        <div class="w-100">
            {{-- Header --}}
            <div class="text-center mb-4">
                <h1 class="brand-logo mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 2.5rem; color: #1f0822;">
                    InternStay
                </h1>
                <p class="text-muted">Create your account to get started</p>
            </div>

            {{-- Register Card --}}
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-sm-5">
                    <h4 class="fw-bold mb-4 text-center" style="color: #1f0822;">Create Account</h4>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                    </svg>
                                </span>
                                <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="Enter your name" required autofocus>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="your@email.com" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                       placeholder="Create a strong password" required>
                                <button type="button" class="input-group-text bg-light border-start-0 toggle-password" style="cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4s3.88.668 5.168 1.957A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12s-3.879-.668-5.168-1.957A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0 border-end-0" 
                                       placeholder="Confirm your password" required>
                                <button type="button" class="input-group-text bg-light border-start-0 toggle-password" style="cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4s3.88.668 5.168 1.957A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12s-3.879-.668-5.168-1.957A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                            Create Account
                        </button>

                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1 border-light-subtle">
                            <span class="mx-3 text-muted small text-uppercase">or</span>
                            <hr class="flex-grow-1 border-light-subtle">
                        </div>

                        <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 py-3 d-flex align-items-center justify-content-center border-light-subtle mb-3" style="border-radius: 12px; font-weight: 600; background-color: #fff; border: 1px solid #dee2e6; color: #212529; transition: all 0.2s ease;">
                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.5 24c0-1.61-.15-3.16-.42-4.67H24v8.86h12.64c-.55 2.87-2.17 5.31-4.6 6.95l7.15 5.54C43.34 35.32 46.5 30.13 46.5 24z"/>
                                <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.15-5.54c-1.99 1.33-4.51 2.1-8.74 2.1-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            </svg>
                            Continue with Google
                        </a>

                        <div class="text-center">
                            <span class="text-muted small">Already have an account?</span>
                            <a href="{{ route('student.login') }}" class="fw-semibold text-decoration-none ms-1">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
