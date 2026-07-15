@extends('layouts.template')

@section('content')

{{-- Page Header --}}
<div class="mb-4">
    <h2 class="fw-bold mb-2" style="color: #1f0822;">My Profile</h2>
    <p class="text-muted">Manage your account information</p>
</div>

{{-- Profile Card --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-sm-5">
                {{-- Avatar Section --}}
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 100px; height: 100px; background-color: rgba(111, 66, 193, 0.1); font-size: 2.5rem; color: #6f42c1; font-weight: 700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>

                <hr class="my-4">

                {{-- Profile Information --}}
                <h5 class="fw-bold mb-4" style="color: #1f0822;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 text-purple" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                    </svg>
                    Account Information
                </h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded h-100" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <form method="POST" action="{{ route('user.profile.update') }}" class="m-0" id="name-edit-form">
                                @csrf
                                @method('PUT')
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="small text-muted mb-0">Name</label>
                                    <button type="submit" class="btn btn-link p-0 text-purple text-decoration-none small fw-semibold" id="edit-save-btn" style="font-size: 0.75rem; display: none;">Save</button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-purple flex-shrink-0" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                    </svg>
                                    <input type="text" name="name" id="name-input" class="form-control form-control-sm border-0 bg-transparent fw-bold p-0 text-dark" value="{{ $user->name }}" style="box-shadow: none; font-size: 1rem;" readonly required>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none text-muted" id="edit-toggle-btn" style="font-size: 0.85rem;" title="Edit Name">
                                        ✏️
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <label class="small text-muted mb-1">Email Address</label>
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 text-purple" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                </svg>
                                <strong>{{ $user->email }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <label class="small text-muted mb-1">Account Status</label>
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 text-success" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <label class="small text-muted mb-1">Member Since</label>
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 text-purple" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                                <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Notification Preferences Section --}}
                <h5 class="fw-bold mb-4" style="color: #1f0822;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 text-purple" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                    </svg>
                    Notification Preferences
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('preferences.update') }}">
                    @csrf
                    
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-3">Notification Frequency</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded {{ $preferences->notification_frequency === 'daily' ? 'border-primary bg-purple-light' : '' }}">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="notification_frequency" id="freq_daily" value="daily" {{ $preferences->notification_frequency === 'daily' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="freq_daily">
                                            <strong>Daily 📅</strong>
                                            <span class="d-block small text-muted">Once a day</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded {{ $preferences->notification_frequency === 'weekly' ? 'border-primary bg-purple-light' : '' }}">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="notification_frequency" id="freq_weekly" value="weekly" {{ $preferences->notification_frequency === 'weekly' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="freq_weekly">
                                            <strong>Weekly 📅</strong>
                                            <span class="d-block small text-muted">Once a week</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded {{ $preferences->notification_frequency === 'off' ? 'border-primary bg-purple-light' : '' }}">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="notification_frequency" id="freq_off" value="off" {{ $preferences->notification_frequency === 'off' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="freq_off">
                                            <strong>Turn Off ✕</strong>
                                            <span class="d-block small text-muted">No emails</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="notify_internships" id="notify_internships" {{ $preferences->notify_internships ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_internships">
                                    <strong>Internships</strong>
                                    <span class="d-block small text-muted">Notify about new jobs</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="notify_rentals" id="notify_rentals" {{ $preferences->notify_rentals ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_rentals">
                                    <strong>Rentals</strong>
                                    <span class="d-block small text-muted">Notify about rooms</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 border-top pt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-0">Preferred Industries</label>
                                
                                {{-- Industry Add Dropdown --}}
                                <div class="dropdown" id="industryDropdownContainer">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-1 py-1.5 px-3" 
                                            type="button" id="addIndustryBtn" data-bs-toggle="dropdown" aria-expanded="false" 
                                            style="border-radius: 8px; font-size: 0.8rem; border-color: #6f42c1; color: #6f42c1;">
                                        ➕ Add Industry
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" id="industryDropdownMenu" style="border-radius: 12px; max-height: 250px; overflow-y: auto; font-size: 0.85rem;">
                                        {{-- Populated dynamically --}}
                                    </ul>
                                </div>
                            </div>

                            {{-- Active Tags Container --}}
                            <div class="d-flex flex-wrap gap-2 p-3 rounded" id="industryTagsContainer" style="background-color: rgba(111, 66, 193, 0.03); border: 1px dashed rgba(111, 66, 193, 0.15); min-height: 58px;">
                                {{-- Populated dynamically --}}
                            </div>

                            {{-- Hidden Select for standard form submission --}}
                            @php
                            $industryLabels = [
                                'IT/Information Technology' => 'IT / Tech',
                                'Engineering' => 'Engineering',
                                'Business/Accounting/Finance' => 'Business / Finance',
                                'Healthcare/Medical' => 'Healthcare',
                                'Creative/Design' => 'Creative / Design',
                                'Admin/Human Resource' => 'Admin / HR',
                                'Build/Architecture/Construction' => 'Construction'
                            ];
                            @endphp
                            <select class="d-none" name="preferred_industries[]" id="hiddenIndustrySelect" multiple>
                                @foreach($industries as $industry)
                                    <option value="{{ $industry }}" {{ in_array($industry, $preferences->preferred_industries ?? []) ? 'selected' : '' }}>
                                        {{ $industryLabels[$industry] ?? $industry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 text-end border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            Save Notification Settings
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <!--{{-- Security Section --}}
                <h5 class="fw-bold mb-3" style="color: #1f0822;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 text-purple" viewBox="0 0 16 16">
                        <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                    </svg>
                    Security
                </h5>

                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: rgba(255, 255, 255, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div>
                        <strong class="d-block mb-1">Sign Out</strong>
                        <small class="text-muted">Log out of your account on this device</small>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>-->
            </div>
        </div>
    </div>

    {{-- Quick Stats Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%);">
            <div class="card-body text-white p-4">
                <h6 class="text-white-50 mb-2">Saved Favourites</h6>
                <h2 class="fw-bold mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                    View All
                </h2>
                <a href="{{ route('favorites.index') }}" class="btn btn-light btn-sm mt-3">Go to Favourites ➔</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Quick Actions ⚡</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('public.internships.index') }}" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                        </svg>
                        Browse Internships
                    </a>
                    <a href="{{ route('public.rentals.index') }}" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                        </svg>
                        Find Accommodation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hiddenSelect = document.getElementById('hiddenIndustrySelect');
    const tagsContainer = document.getElementById('industryTagsContainer');
    const dropdownMenu = document.getElementById('industryDropdownMenu');
    
    // Array of all options { value: string, text: string }
    const allOptions = Array.from(hiddenSelect.options).map(opt => ({
        value: opt.value,
        text: opt.text.trim()
    }));

    function getSelectedValues() {
        return Array.from(hiddenSelect.options)
            .filter(opt => opt.selected)
            .map(opt => opt.value);
    }

    function updateSelectState(selectedValues) {
        Array.from(hiddenSelect.options).forEach(opt => {
            opt.selected = selectedValues.includes(opt.value);
        });
    }

    function render() {
        const selectedValues = getSelectedValues();
        
        // 1. Render tags
        tagsContainer.innerHTML = '';
        if (selectedValues.length === 0) {
            tagsContainer.innerHTML = `<span class="text-muted small my-1">No preferred industries selected. Click "Add Industry" to add.</span>`;
        } else {
            selectedValues.forEach(val => {
                const label = allOptions.find(o => o.value === val)?.text || val;
                
                const tag = document.createElement('span');
                tag.className = 'badge d-inline-flex align-items-center gap-2 py-2 px-3 fw-medium';
                tag.style.cssText = `
                    background-color: rgba(111, 66, 193, 0.08); 
                    color: #6f42c1; 
                    border: 1px solid rgba(111, 66, 193, 0.15); 
                    border-radius: 30px; 
                    font-size: 0.85rem;
                `;
                tag.innerHTML = `
                    ${label}
                    <span class="remove-tag-btn" data-value="${val}" style="cursor: pointer; font-weight: 700; opacity: 0.65; transition: opacity 0.15s ease;">&times;</span>
                `;
                
                // Add hover interactions to X button
                const xBtn = tag.querySelector('.remove-tag-btn');
                xBtn.addEventListener('mouseenter', () => xBtn.style.opacity = '1');
                xBtn.addEventListener('mouseleave', () => xBtn.style.opacity = '0.65');
                xBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    removeIndustry(val);
                });
                
                tagsContainer.appendChild(tag);
            });
        }

        // 2. Render dropdown options (only show non-selected)
        dropdownMenu.innerHTML = '';
        const unselectedOptions = allOptions.filter(o => !selectedValues.includes(o.value));
        
        if (unselectedOptions.length === 0) {
            dropdownMenu.innerHTML = `<li><span class="dropdown-item text-muted disabled">All industries added</span></li>`;
        } else {
            unselectedOptions.forEach(opt => {
                const li = document.createElement('li');
                li.innerHTML = `<a class="dropdown-item py-2" href="#" data-value="${opt.value}">${opt.text}</a>`;
                li.querySelector('a').addEventListener('click', function (e) {
                    e.preventDefault();
                    addIndustry(opt.value);
                });
                dropdownMenu.appendChild(li);
            });
        }
    }

    function addIndustry(value) {
        const current = getSelectedValues();
        if (!current.includes(value)) {
            current.push(value);
            updateSelectState(current);
            render();
        }
    }

    function removeIndustry(value) {
        const current = getSelectedValues().filter(v => v !== value);
        updateSelectState(current);
        render();
    }

    // Inline Name Editing Logic
    const nameInput = document.getElementById('name-input');
    const editToggleBtn = document.getElementById('edit-toggle-btn');
    const editSaveBtn = document.getElementById('edit-save-btn');
    
    if (editToggleBtn && nameInput && editSaveBtn) {
        editToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            nameInput.removeAttribute('readonly');
            nameInput.classList.remove('border-0', 'bg-transparent', 'p-0');
            nameInput.classList.add('px-2', 'py-1');
            nameInput.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
            nameInput.style.border = '1px solid #6f42c1';
            nameInput.style.borderRadius = '8px';
            
            nameInput.focus();
            nameInput.select();
            
            editToggleBtn.style.display = 'none';
            editSaveBtn.style.display = 'inline';
        });
    }

    // Initial render
    render();
});
</script>
@endpush

@endsection
