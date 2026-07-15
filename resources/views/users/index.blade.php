@extends('layouts.sidebar')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #1f0822;">User Management</h2>
        <p class="text-muted mb-0">Manage system users and permissions</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add User
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

{{-- Users Table Card --}}
<form id="bulk-delete-form" action="{{ route('users.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete(this);">
    @csrf
    @method('DELETE')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0" style="color: #1f0822;">All Users</h5>
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
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4" style="width: 45px;"><input type="checkbox" id="select-all" class="form-check-input"></th>
                            <th class="py-3 px-2" style="width: 8%;">No</th>
                            <th class="py-3" style="width: 25%;">Name</th>
                            <th class="py-3" style="width: 30%;">Email</th>
                            <th class="py-3" style="width: 15%;">Joined</th>
                            <th class="py-3 px-4" style="width: 22%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="px-4 align-middle">
                                <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="form-check-input row-checkbox">
                            </td>
                            <td class="px-2 align-middle">
                                <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                        <strong class="text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</strong>
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td class="align-middle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1 text-muted" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                </svg>
                                {{ $user->email }}
                            </td>
                            <td class="align-middle">
                                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                            </td>
                            <td class="px-4 align-middle">
                                <a class="btn btn-info btn-sm me-1" href="{{ route('users.show', $user->id) }}">Show</a>
                                <a class="btn btn-warning btn-sm me-1" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ccc" class="mb-3" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                </svg>
                                <p class="text-muted mb-0">No users found</p>
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
@foreach($users as $user)
    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
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
        return confirm('Are you sure you want to permanently delete the ' + checkedCount + ' selected user(s)?');
    }
</script>

@endsection