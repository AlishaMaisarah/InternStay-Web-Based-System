@extends('layouts.sidebar')

@section('content')
<div class="container-fluid">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success custom-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1" style="color: #1f0822;">Welcome, {{ $profile->pic_name ?? Auth::user()->name }}!</h2>
        <p class="text-muted">Manage your corporate profile and internship listings here.</p>
    </div>

    {{-- Company Verification Status Banner --}}
    <div class="mb-4">
        @if(!Auth::user()->hasVerifiedEmail())
            <div class="alert alert-warning border-0 shadow-sm p-4 text-start" style="border-left: 6px solid #ffc107 !important; border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <span class="fs-3 me-3">⚠</span>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Please verify your company email to continue.</h5>
                        <p class="mb-0 text-muted small">A verification link was sent to {{ Auth::user()->email }}. Please click the link in your email to activate your account features.</p>
                    </div>
                </div>
            </div>
        @elseif($profile->verification_status === 'Pending')
            <div class="alert alert-warning border-0 shadow-sm p-4 text-start" style="border-left: 6px solid #ffc107 !important; border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <span class="fs-3 me-3">🟡</span>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Your company email has been verified.</h5>
                        <p class="mb-0 text-muted small">Your verification document is currently under review by our administrator team.</p>
                    </div>
                </div>
            </div>
        @elseif($profile->verification_status === 'Approved')
            <div class="alert alert-success border-0 shadow-sm p-4 text-start mb-4" style="border-left: 6px solid #198754 !important; border-radius: 16px; background-color: #d1e7dd; color: #0f5132;">
                <div class="d-flex align-items-center">
                    <span class="fs-3 me-3">🟢</span>
                    <div>
                        <h5 class="fw-bold mb-1">Your company account has been approved.</h5>
                        <p class="mb-0 small">You may now post internship listings.</p>
                    </div>
                </div>
            </div>
        @elseif($profile->verification_status === 'Rejected')
            <div class="alert alert-danger border-0 shadow-sm p-4 text-start" style="border-left: 6px solid #dc3545 !important; border-radius: 16px;">
                <div class="d-flex align-items-start">
                    <span class="fs-3 me-3">🔴</span>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Your verification request has been rejected.</h5>
                        <p class="mb-2 text-muted small">Reason: <strong>{{ $profile->rejection_reason ?? 'No reason provided.' }}</strong></p>
                        <p class="mb-0 small text-danger">Please upload a new verification document or contact support.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Split Layout Based on Status --}}
    @if($profile->verification_status === 'Pending')
        
        {{-- PENDING VERIFICATION STATE --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-left: 6px solid #ffc107 !important; border-radius: 16px;">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-2">Account Verification Pending</h4>
                            <p class="text-muted mb-0">
                                Your company profile is currently undergoing verification by our administration team. 
                                Once approved, you will gain access to manually post and manage internship listings on our platform.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-purple" style="color: #6f42c1;">Your Submitted Verification Document</h5>
                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-3 p-2 border me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#6f42c1" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Verification Document</span>
                                <small class="text-muted">Uploaded on {{ $profile->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $profile->document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            View Document
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-dark">Profile Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Company Name</small>
                            <span class="fw-semibold text-dark">{{ $profile->company_name }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">PIC Email</small>
                            <span class="fw-semibold text-dark">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">PIC Phone</small>
                            <span class="fw-semibold text-dark">{{ $profile->phone }}</span>
                        </li>
                        <li class="mb-0">
                            <small class="text-muted d-block">PIC Position</small>
                            <span class="fw-semibold text-dark">{{ $profile->position }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    @elseif($profile->verification_status === 'Rejected')
        
        {{-- REJECTED STATE --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-left: 6px solid #dc3545 !important; border-radius: 16px;">
                    <div class="d-flex align-items-start">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-2">Account Verification Rejected</h4>
                            <p class="text-muted mb-3">
                                Unfortunately, your company account verification was rejected. Please review the comments below and upload new documents or contact support.
                            </p>
                            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3 fw-semibold">
                                Reason: {{ $profile->rejection_reason ?? 'No remarks provided.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-purple" style="color: #6f42c1;">Your Submitted Verification Document</h5>
                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-3 p-2 border me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#6f42c1" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Verification Document</span>
                                <small class="text-muted">Uploaded on {{ $profile->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $profile->document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            View Document
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-dark">Profile Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Company Name</small>
                            <span class="fw-semibold text-dark">{{ $profile->company_name }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">PIC Email</small>
                            <span class="fw-semibold text-dark">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">PIC Phone</small>
                            <span class="fw-semibold text-dark">{{ $profile->phone }}</span>
                        </li>
                        <li class="mb-0">
                            <small class="text-muted d-block">PIC Position</small>
                            <span class="fw-semibold text-dark">{{ $profile->position }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    @else
        
        {{-- APPROVED / ACTIVE PORTAL --}}
        
        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Internship Posts</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                </svg>
                            </div>
                        </div>
                        <small class="text-white-50">Manually posted</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-white-50 mb-1">Active Posts</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['active'] }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                </svg>
                            </div>
                        </div>
                        <small class="text-white-50">Visible to candidates</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-white-50 mb-1">Suspended Posts</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['suspended'] }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                </svg>
                            </div>
                        </div>
                        <small class="text-white-50">Hidden by Admin</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Internship Postings CRUD --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0" style="color: #1f0822;">Your Posted Internship Listings</h5>
                    <a href="{{ route('company.internships.create') }}" class="btn btn-primary" style="background-color: #6f42c1 !important; border-color: #6f42c1 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Post New Internship
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 35%">Title</th>
                                <th style="width: 15%">Industry</th>
                                <th style="width: 20%">Location</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($internships as $internship)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $internship->internship_name }}</div>
                                        <small class="text-muted">Posted {{ $internship->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>{{ $internship->industry }}</td>
                                    <td>{{ $internship->location }}</td>
                                    <td>
                                        @if($internship->is_suspended)
                                            <span class="badge bg-danger">Suspended</span>
                                        @elseif($internship->is_closed)
                                            <span class="badge bg-secondary">Closed</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('company.internships.edit', $internship) }}" class="btn btn-sm btn-outline-warning">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('company.internships.destroy', $internship) }}" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="background-color: #dc3545 !important; border-color: #dc3545 !important;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                        </svg>
                                        <p class="text-muted">You haven't posted any internships yet.</p>
                                        <a href="{{ route('company.internships.create') }}" class="btn btn-primary" style="background-color: #6f42c1 !important; border-color: #6f42c1 !important;">
                                            Post Your First Internship
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

</div>
@endsection
