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

        {{-- Right Side - Forgot Password Form --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-2" style="color: #1f0822;">Reset Password</h3>
                        <p class="text-muted">Enter your email to receive a password reset link</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

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

                        <button type="submit" class="btn btn-primary w-100 py-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            Send Password Reset Link
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-box {
    transition: transform 0.2s ease;
}
.feature-box:hover {
    transform: translateY(-4px);
}
</style>
@endsection
