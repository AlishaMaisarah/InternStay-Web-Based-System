@extends('layouts.auth')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="row min-vh-100 align-items-center justify-content-center py-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #1f0822;">Verification Failed</h2>
                    <p class="text-muted mb-4">
                        The email verification link is invalid or corrupted. Please make sure you copied the entire URL.
                    </p>
                </div>

                <div class="d-grid gap-2 mb-3">
                    <a href="{{ route('login.role') }}" class="btn btn-primary btn-lg py-3 fw-semibold">
                        Return to Login Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
