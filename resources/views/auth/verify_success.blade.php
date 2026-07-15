@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="row min-vh-100 align-items-center justify-content-center py-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l4.992-5.99a.75.75 0 0 0-.018-1.042z"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #1f0822;">Email Verified Successfully!</h2>
                    <p class="lead text-muted mb-4" style="font-size: 1.1rem;">
                        ✅ Your email address has been verified successfully.
                    </p>
                    <p class="text-secondary mb-4">
                        You may now return to InternStay and log in to your account.
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('login.role') }}" class="btn btn-primary btn-lg py-3 fw-semibold">
                        Go to Login Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
