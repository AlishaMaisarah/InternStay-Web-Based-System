@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="row min-vh-100 align-items-center justify-content-center py-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            <path d="M8 3.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8.5 7.71V4a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #1f0822;">Verification Link Expired</h2>
                    <p class="text-muted mb-4">
                        This email verification link has expired for security reasons. Please request a new verification email.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.resend.unauth') }}" class="mb-3">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label fw-semibold">Enter your email address</label>
                        <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold">
                        Resend Verification Link
                    </button>
                </form>

                <div class="mt-3">
                    <a href="{{ route('login.role') }}" class="text-decoration-none small text-muted">
                        Return to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
