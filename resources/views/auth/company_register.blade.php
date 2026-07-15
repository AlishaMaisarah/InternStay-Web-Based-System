@extends('layouts.auth')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-5">
                        <h1 class="brand-logo mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #1f0822; font-size: 2.2rem;">
                            InternStay
                        </h1>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 fw-bold">COMPANY REGISTRATION</span>
                        <p class="text-muted">Register your company details and representative PIC account.</p>
                    </div>

                    <form method="POST" action="{{ route('company.register') }}" enctype="multipart/form-data">
                        @csrf

                        <h5 class="fw-bold mb-4 text-purple border-bottom pb-2" style="color: #6f42c1;">Company Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Company Name</label>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                                       value="{{ old('company_name') }}" placeholder="e.g. Intel Malaysia" required autofocus>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">PIC Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Work Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="e.g. pic@intel.com" required>
                                @if(app()->environment('local'))
                                    <div class="form-text text-info small fw-semibold">
                                        <i class="bi bi-info-circle me-1"></i>Development Mode: Personal email providers are allowed for testing email verification.
                                    </div>
                                @else
                                    <div class="form-text text-muted small">
                                        For security purposes, please register using your official company email address.
                                    </div>
                                @endif
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">PIC Phone Number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" placeholder="e.g. +60123456789" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Position / Designation</label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" 
                                       value="{{ old('position') }}" placeholder="e.g. HR Manager" required>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Verification Document (PDF/Image)</label>
                                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" required>
                                <div class="form-text text-muted small">Upload Employer Verification Letter (PDF) OR Staff ID (JPG/PNG). Max 5MB.</div>
                                @error('document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 mt-4 text-purple border-bottom pb-2" style="color: #6f42c1;">Security Settings</h5>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" required>
                                    <button type="button" class="input-group-text bg-white border-start-0 toggle-password" style="cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4s3.88.668 5.168 1.957A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12s-3.879-.668-5.168-1.957A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control border-end-0" required>
                                    <button type="button" class="input-group-text bg-white border-start-0 toggle-password" style="cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4s3.88.668 5.168 1.957A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12s-3.879-.668-5.168-1.957A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 mb-4 mt-3" style="background-color: #6f42c1 !important; border-color: #6f42c1 !important; font-weight: 600;">
                            Submit Registration for Review
                        </button>

                        <div class="text-center">
                            <span class="text-muted small">Already registered?</span>
                            <a href="{{ route('company.login') }}" class="fw-semibold text-decoration-none ms-1" style="color: #6f42c1;">Login Here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
