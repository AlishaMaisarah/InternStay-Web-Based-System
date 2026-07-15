@extends('layouts.sidebar')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #1f0822;">Rental Accommodation Management</h2>
        <p class="text-muted mb-0">Manage and scrape rental property listings</p>
    </div>
    <a href="{{ route('rentals.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Rental Property
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

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        {!! session('warning') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Scrape Rentals Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3" style="color: #1f0822;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
            </svg>
            Scrape Live Listings
        </h5>
        <form action="{{ route('rentals.scrape') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label small text-muted">Active Sources (Simultaneous)</label>
                <div class="d-flex flex-wrap gap-1 align-items-center" style="min-height: 38px;">
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500; background-color: rgba(255, 193, 7, 0.1) !important; color: #b58100 !important; border-color: rgba(255, 193, 7, 0.25) !important;">PropertyGuru</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500; background-color: rgba(0, 55, 255, 0.1) !important; color: #0037ffff !important; border-color: rgba(0, 55, 255, 0.25) !important;">iProperty</span> 
                    <span class="badge bg-opacity-10 border px-2 py-1.5" style="font-size: 0.75rem; font-weight: 500; background-color: rgba(99, 179, 225, 0.1) !important; color: #63b3e1ff !important; border-color: rgba(99, 179, 225, 0.25) !important;">iBilik</span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">State</label>
                <input type="text" name="state" class="form-control" placeholder="e.g., Selangor, Melaka" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">City</label>
                <input type="text" name="city" class="form-control" placeholder="e.g., Shah Alam, Jasin" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success w-100">
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
        <form action="{{ route('rentals.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0" 
                           placeholder="Search property, type, address..." 
                           value="{{ $q ?? request('q') }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-secondary flex-grow-1">Search</button>
                    @if(request('q'))
                        <a href="{{ route('rentals.index') }}" class="btn btn-light">
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

{{-- Rentals Table Card --}}
<form id="bulk-delete-form" action="{{ route('rentals.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete(this);">
    @csrf
    @method('DELETE')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0" style="color: #1f0822;">All Rental Properties</h5>
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
                            <th class="col-name py-3">Property Name</th>
                            <th class="col-type py-3">Type</th>
                            <th class="col-rent py-3">Rent Amount</th>
                            <th class="col-address py-3">Address</th>
                            <th class="col-status py-3">Status</th>
                            <th class="col-source py-3">Source</th>
                            <th class="col-action py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                        <tr>
                            <td class="px-4 align-middle">
                                <input type="checkbox" name="ids[]" value="{{ $rental->id }}" class="form-check-input row-checkbox">
                            </td>
                            <td class="col-no px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </td>
                            <td class="col-name align-middle" title="{{ $rental->property_name }}">
                                <strong>{{ $rental->property_name }}</strong>
                            </td>
                            <td class="col-type align-middle">
                                <span class="badge bg-secondary bg-opacity-10 text-dark">{{ $rental->property_type }}</span>
                            </td>
                            <td class="col-rent align-middle">
                                <strong class="text-success">MYR {{ number_format($rental->rent_amount, 2) }}</strong>
                            </td>
                            <td class="col-address align-middle" title="{{ $rental->address }}">{{ $rental->address }}</td>
                            <td class="col-status align-middle">
                                @if($rental->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Occupied</span>
                                @endif
                            </td>
                            <td class="col-source align-middle">
                                @if($rental->source)
                                    @if(str_contains($rental->source, 'PropertyGuru'))
                                        <span class="badge bg-warning text-dark">PropertyGuru</span>
                                    @elseif(str_contains($rental->source, 'iBilik') || str_contains($rental->source, 'ibilik'))
                                        <span class="badge" style="background-color: #63b3e1ff;">iBilik</span>
                                    @elseif(str_contains($rental->source, 'iProperty'))
                                        <span class="badge" style="background-color: #0037ffff;">iProperty</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $rental->source }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Manual</span>
                                @endif
                            </td>
                            <td class="col-action px-4 align-middle">
                                <a class="btn btn-info btn-sm me-1" href="{{ route('rentals.show', $rental->id) }}">Show</a>
                                <a class="btn btn-warning btn-sm me-1" href="{{ route('rentals.edit', $rental->id) }}">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure?')) { document.getElementById('delete-form-{{ $rental->id }}').submit(); }">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                                <p class="text-muted mb-0">No rental properties found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

{{-- Individual Delete Forms --}}
@foreach($rentals as $rental)
    <form id="delete-form-{{ $rental->id }}" action="{{ route('rentals.destroy', $rental->id) }}" method="POST" class="d-none">
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
        return confirm('Are you sure you want to permanently delete the ' + checkedCount + ' selected rental property/properties?');
    }
</script>

@endsection