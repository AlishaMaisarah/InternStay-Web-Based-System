@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="row min-vh-100 align-items-center justify-content-center py-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg p-4 p-md-5 text-center">
                
                @if (session('resent'))
                    <div class="alert alert-success custom-success border-0 shadow-sm mb-4 text-start" role="alert">
                        A fresh verification link has been sent to your email address.
                    </div>
                @endif

                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #1f0822;">Verify Your Email</h2>
                    <p class="lead text-muted mb-4" style="font-size: 1.05rem;">
                        Before proceeding, please check your email for a verification link.
                    </p>
                    <p class="text-secondary small mb-4">
                        If you did not receive the email, please check your spam folder or request a new one below.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.resend.unauth') }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold">
                        Resend Verification Email
                    </button>
                </form>

                <div class="mt-3">
                    <a href="{{ route('login.role') }}" class="text-decoration-none small text-muted">
                        Back to Login Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
