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
        <h2 class="fw-bold mb-1" style="color: #1f0822;">Company Internship Monitoring</h2>
        <p class="text-muted">Monitor, suspend, or delete internship listings manually posted by verified company representatives.</p>
    </div>

    {{-- Postings List --}}
    <form id="bulk-delete-form" action="{{ route('admin.company-internships.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete(this);">
        @csrf
        @method('DELETE')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0" style="color: #1f0822;">Posted Listings</h5>
                <button type="submit" id="delete-selected-btn" class="btn btn-danger btn-sm d-none" style="background-color: #dc3545 !important; border-color: #dc3545 !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1 mb-0.5" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                    Delete Selected
                </button>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 45px;"><input type="checkbox" id="select-all" class="form-check-input"></th>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Internship Title</th>
                                <th style="width: 20%">Company Profile</th>
                                <th style="width: 15%">Industry & Location</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 13%">Contact Info</th>
                                <th style="width: 12%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($internships as $internship)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $internship->id }}" class="form-check-input row-checkbox">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $internship->internship_name }}</div>
                                        <small class="text-muted">Posted {{ $internship->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-purple" style="color: #6f42c1;">{{ $internship->company }}</div>
                                        <small class="text-muted d-block">PIC: {{ $internship->user->name ?? 'N/A' }}</small>
                                        @if(isset($internship->user->companyProfile))
                                            <span class="badge bg-success bg-opacity-10 text-success small">Verified Partner</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $internship->industry }}</div>
                                        <small class="text-muted">{{ $internship->location }}</small>
                                    </td>
                                    <td>
                                        @if($internship->is_suspended)
                                            <span class="badge bg-danger">Suspended</span>
                                        @elseif($internship->is_closed)
                                            <span class="badge bg-secondary">Closed</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-semibold">{{ $internship->contact_person ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $internship->contact_email }}</div>
                                        <div class="small text-muted">{{ $internship->contact_phone }}</div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm {{ $internship->is_suspended ? 'btn-outline-success' : 'btn-outline-danger' }}" onclick="document.getElementById('suspend-form-{{ $internship->id }}').submit();">
                                                {{ $internship->is_suspended ? 'Unsuspend' : 'Suspend' }}
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger" style="background-color: #dc3545 !important; border-color: #dc3545 !important;" onclick="if(confirm('Are you sure you want to permanently delete this company internship posting?')) { document.getElementById('delete-form-{{ $internship->id }}').submit(); }">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                        </svg>
                                        <p class="text-muted mb-0">No company-posted internships found in the system.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    {{-- Individual Forms (outside bulk form to prevent nesting forms) --}}
    @foreach($internships as $internship)
        <form id="suspend-form-{{ $internship->id }}" method="POST" action="{{ route('admin.company-internships.suspend', $internship) }}" class="d-none">
            @csrf
        </form>

        <form id="delete-form-{{ $internship->id }}" method="POST" action="{{ route('admin.company-internships.destroy', $internship) }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const deleteSelectedBtn = document.getElementById('delete-selected-btn');

        function updateDeleteButtonVisibility() {
            const checkedBoxes = Array.from(rowCheckboxes).filter(cb => cb.checked);
            if (checkedBoxes.length > 0) {
                deleteSelectedBtn.classList.remove('d-none');
            } else {
                deleteSelectedBtn.classList.add('d-none');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateDeleteButtonVisibility();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const allChecked = Array.from(rowCheckboxes).every(rowCb => rowCb.checked);
                selectAllCheckbox.checked = allChecked;
                updateDeleteButtonVisibility();
            });
        });
    });

    function confirmBulkDelete(form) {
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const checkedCount = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
        if (checkedCount === 0) {
            alert('Please select at least one item to delete.');
            return false;
        }
        return confirm('Are you sure you want to permanently delete the ' + checkedCount + ' selected company internship posting(s)?');
    }
</script>
@endsection
