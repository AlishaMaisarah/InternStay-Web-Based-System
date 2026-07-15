@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 1100px;">
    <div class="row align-items-center g-5 min-vh-100 py-5">
        {{-- Left Side - Branding --}}
        <div class="col-lg-6 d-none d-lg-block">
            <div class="p-4">
                <div class="mb-4">
                    <h1 class="brand-logo mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 3rem; color: #1f0822;">
                        InternStay
                    </h1>
                    <p class="lead text-muted mb-4">
                        Your gateway to discovering amazing internship opportunities and finding the perfect accommodation nearby.
                    </p>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.85rem; letter-spacing: 1px;">Platform Features</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-3" style="width: 48px; height: 48px; color: #6f42c1;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 1.05rem;">Internship Matching</h6>
                                <p class="text-muted small mb-0">Discover opportunities that fit your profile.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-3" style="width: 48px; height: 48px; color: #e83e8c;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 1.05rem;">Nearby Rentals</h6>
                                <p class="text-muted small mb-0">Find the perfect accommodation easily.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-3" style="width: 48px; height: 48px; color: #0dcaf0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 1.05rem;">Save Favourites</h6>
                                <p class="text-muted small mb-0">Keep track of your top picks.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-3" style="width: 48px; height: 48px; color: #ffc107;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 1.05rem;">Smart Recommendations</h6>
                                <p class="text-muted small mb-0">Get personalized suggestions.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        @if(isset($role))
                            @if($role === 'student')
                                <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold" style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">STUDENT PORTAL</span>
                                <h3 class="fw-bold mb-2" style="color: #1f0822;">Student Login</h3>
                                <p class="text-muted">Access internships and accommodations nearby</p>
                            @elseif($role === 'company')
                                <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">COMPANY PORTAL</span>
                                <h3 class="fw-bold mb-2" style="color: #1f0822;">Company PIC Login</h3>
                                <p class="text-muted">Manage listings and connect with students</p>
                            @elseif($role === 'admin')
                                <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">ADMIN CONSOLE</span>
                                <h3 class="fw-bold mb-2" style="color: #1f0822;">Admin Login</h3>
                                <p class="text-muted">Manage platform users and postings</p>
                            @endif
                        @else
                            <h3 class="fw-bold mb-2" style="color: #1f0822;">Welcome Back!</h3>
                            <p class="text-muted">Login to continue your journey</p>
                        @endif
                    </div>

                    @if(session('unverified_email'))
                        <div class="alert alert-warning border-0 shadow-sm mb-4 text-start p-3" style="border-left: 5px solid #ffc107 !important; border-radius: 12px;">
                            <div class="d-flex align-items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 mt-1 flex-shrink-0 text-warning" viewBox="0 0 16 16">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                </svg>
                                <div>
                                    <div class="fw-semibold text-dark small mb-1">Verification Needed</div>
                                    <div class="small text-muted mb-2">Didn't get the email? Request a new link below.</div>
                                    <form method="POST" action="{{ route('verification.resend.unauth') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                                        <button type="submit" class="btn btn-sm btn-warning fw-semibold">Resend Verification Email</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if(isset($role))
                            <input type="hidden" name="login_role" value="{{ $role }}">
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
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

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                       placeholder="Enter your password" required>
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

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Login to Account
                        </button>

                        @if(!isset($role) || $role === 'student')
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
                        @endif

                        @if(!isset($role) || $role !== 'admin')
                            <div class="text-center">
                                <span class="text-muted small">Don't have an account?</span>
                                @if(isset($role) && $role === 'company')
                                    <a href="{{ route('company.register') }}" class="fw-semibold text-decoration-none ms-1">Create Account</a>
                                @else
                                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none ms-1">Create Account</a>
                                @endif
                            </div>
                        @endif
                        
                        <div class="text-center mt-3 pt-3 border-top">
                            <a href="{{ route('login.role') }}" class="text-decoration-none small text-muted d-inline-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Back to Role Selection
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
