@extends('layouts.sidebar')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #1f0822;">Internship Management</h2>
        <p class="text-muted mb-0">Manage and scrape internship listings</p>
    </div>
    <a href="{{ route('internships.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Internship
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
        </svg>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Scrape Internships Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
            </svg>
            Scrape Internships
        </h5>
        <form method="POST" action="{{ route('internships.scrape') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label small text-muted">Active Sources (Simultaneous)</label>
                <div class="d-flex flex-wrap gap-1 align-items-center" style="min-height: 38px;">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500;">Hiredly</span>
                    <span class="badge bg-info bg-opacity-10 text-dark border border-info border-opacity-25 px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500; background-color: rgba(13, 202, 240, 0.1) !important; color: #0bacd1 !important; border-color: rgba(13, 202, 240, 0.25) !important;">Jobsora</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500;">LinkedIn</span>
                </div>
            </div>
            <div class="col-md-5">
                <label class="form-label small text-muted">Field / Course</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Field / Course</option>
                    <option value="information-technology">IT/Information Technology</option>
                    <option value="engineering">Engineering</option>
                    <option value="business">Business/Accounting/Finance</option>
                    <option value="healthcare">Healthcare/Medical</option>
                    <option value="construction">Build/Architecture/Construction</option>
                    <option value="creative">Creative/Design</option>
                    <option value="admin">Admin/Human Resource</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted" title="Limit per source website">Limit per Source</label>
                <input type="number" name="limit" class="form-control" value="10" min="1" max="30">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                    </svg>
                    Scrape All
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Search Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('internships.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0" 
                           placeholder="Search internship, company, industry, location..." 
                           value="{{ $q ?? request('q') }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-secondary flex-grow-1">Search</button>
                    @if(request('q'))
                        <a href="{{ route('internships.index') }}" class="btn btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Internships Table Card --}}
<form id="bulk-delete-form" action="{{ route('internships.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete(this);">
    @csrf
    @method('DELETE')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0" style="color: #1f0822;">All Internships</h5>
            <button type="submit" id="delete-selected-btn" class="btn btn-danger btn-sm d-none" style="background-color: #dc3545 !important; border-color: #dc3545 !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1 mb-0.5" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                Delete Selected
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-fixed mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4" style="width: 55px;"><input type="checkbox" id="select-all" class="form-check-input"></th>
                            <th class="col-no py-3 px-2">No</th>
                            <th class="col-name py-3">Internship Name</th>
                            <th class="col-company py-3">Company</th>
                            <th class="col-industry py-3">Industry</th>
                            <th class="col-location py-3">Location</th>
                            <th class="col-source py-3">Source</th>
                            <th class="col-action py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($internships as $internship)
                        <tr>
                            <td class="px-4 align-middle">
                                <input type="checkbox" name="ids[]" value="{{ $internship->id }}" class="form-check-input row-checkbox">
                            </td>
                            <td class="col-no px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </td>
                            <td class="col-name align-middle" title="{{ $internship->internship_name }}">
                                <strong>{{ $internship->internship_name }}</strong>
                            </td>
                            <td class="col-company align-middle" title="{{ $internship->company }}">
                                {{ $internship->company }}
                            </td>
                            <td class="col-industry align-middle">
                                <span class="badge bg-secondary bg-opacity-10 text-dark">{{ $internship->industry }}</span>
                            </td>
                            <td class="col-location align-middle">{{ $internship->location }}</td>
                            <td class="col-source align-middle">
                                @php
                                    $s = strtolower($internship->source);
                                @endphp
                                @if($s === 'hiredly')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Hiredly</span>
                                @elseif($s === 'jobstreet')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1" style="background-color: rgba(13, 110, 253, 0.1) !important;">JobStreet</span>
                                @elseif($s === 'jobsora')
                                    <span class="badge bg-info bg-opacity-10 text-dark border border-info border-opacity-25 px-2 py-1" style="background-color: rgba(13, 202, 240, 0.1) !important; color: #0bacd1 !important; border-color: rgba(13, 202, 240, 0.25) !important;">Jobsora</span>
                                @elseif($s === 'linkedin')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">LinkedIn</span>
                                @elseif($s === 'mock')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Mock</span>
                                @elseif($s)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">{{ $internship->source }}</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Manual</span>
                                @endif
                            </td>
                            <td class="col-action px-4 align-middle">
                                <a class="btn btn-info btn-sm me-1" href="{{ route('internships.show', $internship->id) }}">Show</a>
                                <a class="btn btn-warning btn-sm me-1" href="{{ route('internships.edit', $internship->id) }}">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure?')) { document.getElementById('delete-form-{{ $internship->id }}').submit(); }">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                </svg>
                                <p class="text-muted mb-0">No internships found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

{{-- Individual Delete Forms (outside bulk form to prevent nesting forms) --}}
@foreach($internships as $internship)
    <form id="delete-form-{{ $internship->id }}" action="{{ route('internships.destroy', $internship->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach

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
        return confirm('Are you sure you want to permanently delete the ' + checkedCount + ' selected internship(s)?');
    }
</script>

@endsection
