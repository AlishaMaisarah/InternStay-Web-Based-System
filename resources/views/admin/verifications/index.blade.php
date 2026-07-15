@extends('layouts.sidebar')

@section('content')
<div class="container-fluid">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success custom-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1" style="color: #1f0822;">Company Verification Management</h2>
        <p class="text-muted">Review, approve, or reject company representative registration requests.</p>
    </div>

    {{-- Verification Requests Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th style="width: 4%">No</th>
                            <th style="width: 18%">Company Name</th>
                            <th style="width: 18%">PIC Info</th>
                            <th style="width: 12%">Email Verified</th>
                            <th style="width: 14%">Submitted Document</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 12%">Reviewed By</th>
                            <th style="width: 12%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold">{{ $profile->company_name }}</div>
                                    <small class="text-muted">Uploaded {{ $profile->created_at->format('d M Y, h:i A') }}</small>
                                </td>
                                <td>
                                    <div>{{ $profile->pic_name ?? ($profile->user->name ?? 'N/A') }}</div>
                                    <div class="small text-muted">{{ $profile->user->email ?? 'N/A' }}</div>
                                    <div class="small text-muted">{{ $profile->phone }} | {{ $profile->position }}</div>
                                </td>
                                <td>
                                    @if($profile->user && $profile->user->hasVerifiedEmail())
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $profile->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                        </svg>
                                        View Document
                                    </a>
                                </td>
                                <td>
                                    @if($profile->verification_status === 'Pending')
                                        <span class="badge bg-warning text-dark">Pending Review</span>
                                    @elseif($profile->verification_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($profile->verification_status === 'Rejected')
                                        <span class="badge bg-danger" data-bs-toggle="tooltip" title="Reason: {{ $profile->rejection_reason }}">Rejected 🛈</span>
                                    @endif
                                </td>
                                <td>
                                    @if($profile->reviewed_by)
                                        <div>{{ $profile->reviewer->name ?? 'Admin' }}</div>
                                        <small class="text-muted">{{ $profile->reviewed_at->format('d M Y') }}</small>
                                    @else
                                        <span class="text-muted small">Not reviewed yet</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($profile->verification_status === 'Pending')
                                        <div class="d-flex justify-content-end gap-2">
                                            <form method="POST" action="{{ route('admin.verifications.approve', $profile) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" style="background-color: #198754 !important; border-color: #198754 !important;">
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" style="background-color: #dc3545 !important; border-color: #dc3545 !important;"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $profile->id }}">
                                                Reject
                                            </button>
                                        </div>

                                        {{-- Reject Modal --}}
                                        <div class="modal fade" id="rejectModal-{{ $profile->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" style="text-align: left;">
                                            <div class="modal-dialog">
                                                <div class="modal-content" style="border-radius: 16px;">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="rejectModalLabel">Reject Company Verification</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.verifications.reject', $profile) }}">
                                                        @csrf
                                                        <div class="modal-body py-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Rejection Remarks / Reason</label>
                                                                <textarea name="rejection_reason" class="form-control" rows="4" 
                                                                          placeholder="e.g. Uploaded staff ID is expired or document is illegible..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger" style="background-color: #dc3545 !important; border-color: #dc3545 !important;">
                                                                Submit Rejection
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Action completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                    </svg>
                                    <p class="text-muted mb-0">No company verification requests found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    // Initialize tooltips if Bootstrap handles them
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
