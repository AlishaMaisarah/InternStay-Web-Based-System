@extends('layouts.sidebar')

@section('content')

{{-- Success Alert --}}
@if (session('recommendation_results'))
    @php $res = session('recommendation_results'); @endphp
    <div class="alert alert-success border-0 shadow-sm mb-4 p-4" style="background: rgba(25, 135, 84, 0.1); border-radius: 16px; color: #146c43;">
        <div class="d-flex align-items-start gap-3">
            <div class="bg-success text-white rounded-circle p-2 d-inline-flex" style="flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Recommendation Emails Sent</h5>
                <ul class="list-unstyled mb-0 small">
                    <li>Processed Users: <strong>{{ $res['processed'] }}</strong></li>
                    <li>Emails Sent: <strong>{{ $res['sent'] }}</strong></li>
                    <li>Skipped (Already notified): <strong>{{ $res['skipped_already_notified'] }}</strong></li>
                    <li>Skipped (No recommendations): <strong>{{ $res['skipped_no_recommendations'] }}</strong></li>
                    <li>Failed: <strong>{{ $res['failed'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Welcome Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold mb-2" style="color: #1f0822;">Welcome Back, {{ Auth::user()->name }}!</h2>
        <p class="text-muted mb-0">Here's what's happening with your internship platform today.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#confirmEmailsModal">
            <span>📧</span> Send Recommendation Emails
        </button>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ba68c8 0%, #ce93d8 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Internships</h6>
                        <h2 class="fw-bold mb-0">{{ $internships->count() }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Active listings</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Available Rentals</h6>
                        <h2 class="fw-bold mb-0">{{ $rentals->where('is_available', true)->count() }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Ready to rent</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #c20e35 0%, #ebb4bf 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Properties</h6>
                        <h2 class="fw-bold mb-0">{{ $rentals->count() }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">In database</small>
            </div>
        </div>
    </div>
</div>

{{-- Company Portal Statistics --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #7b1fa2 0%, #ab47bc 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Companies</h6>
                        <h2 class="fw-bold mb-0">{{ $totalCompanies }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Registered partners</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f57c00 0%, #ffb74d 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Pending Verifications</h6>
                        <h2 class="fw-bold mb-0">{{ $pendingVerifications }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm-1-9a1 1 0 1 0 2 0v3a1 1 0 1 0-2 0V6zm0 5a1 1 0 1 0 2 0v.01a1 1 0 1 0-2 0V11z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Awaiting review</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #388e3c 0%, #81c784 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Approved Companies</h6>
                        <h2 class="fw-bold mb-0">{{ $approvedCompanies }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Verified partners</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0288d1 0%, #4fc3f7 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white-50 mb-1">Company-Posted Listings</h6>
                        <h2 class="fw-bold mb-0">{{ $companyPostedInternships }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                        </svg>
                    </div>
                </div>
                <small class="text-white-50">Active manual listings</small>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
            </svg>
            Quick Actions
        </h5>

        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('internships.index') }}" class="btn btn-primary w-100 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mb-2" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                    </svg>
                    <div class="fw-semibold">View Internships</div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('internships.create') }}" class="btn btn-outline-primary w-100 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mb-2" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    <div class="fw-semibold">Add Internship</div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('rentals.index') }}" class="btn btn-success w-100 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mb-2" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                    </svg>
                    <div class="fw-semibold">View Rentals</div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('rentals.create') }}" class="btn btn-outline-success w-100 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mb-2" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    <div class="fw-semibold">Add Rental</div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Recent Internships --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
            </svg>
            Recent Internships
        </h5>

        <div class="list-group list-group-flush">
            @forelse ($recentInternships as $internship)
                <div class="list-group-item border-0 px-0 py-3">
                    <div class="d-flex align-items-start">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#6f42c1" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">{{ $internship->internship_name }}</h6>
                            <p class="mb-1 text-muted small">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                </svg>
                                {{ $internship->company }}
                            </p>
                            <p class="mb-0 text-muted small">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                {{ $internship->location }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                        <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                    </svg>
                    <p class="text-muted">No internships added yet.</p>
                    <a href="{{ route('internships.create') }}" class="btn btn-sm btn-primary">Add Your First Internship</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@section('modals')
{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmEmailsModal" tabindex="-1" aria-labelledby="confirmEmailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="confirmEmailsModalLabel" style="color: #1f0822;">Send Recommendation Emails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-muted">
                This will send recommendation emails to eligible users based on their saved preferences and notification frequency. Continue?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 12px;" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.send-recommendations') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 py-2">Send Emails</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
