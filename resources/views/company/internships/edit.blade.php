@extends('layouts.sidebar')

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb / Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f0822;">Edit Internship Listing</h2>
            <p class="text-muted">Modify details of your posted opportunity.</p>
        </div>
        <!--<a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>-->
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-sm-5">
            <form method="POST" action="{{ route('company.internships.update', $internship) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Left Column - Core Fields --}}
                    <div class="col-lg-6">
                        <h5 class="fw-bold mb-4 text-purple border-bottom pb-2" style="color: #6f42c1;">Internship Details</h5>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Internship Title</label>
                            <input type="text" name="internship_name" class="form-control @error('internship_name') is-invalid @enderror" 
                                   value="{{ old('internship_name', $internship->internship_name) }}" required>
                            @error('internship_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Industry</label>
                            <select name="industry" class="form-select @error('industry') is-invalid @enderror" required>
                                <option value="" disabled>Select industry</option>
                                <option value="IT/Information Technology" {{ old('industry', $internship->industry) == 'IT/Information Technology' ? 'selected' : '' }}>IT / Tech</option>
                                <option value="Engineering" {{ old('industry', $internship->industry) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Business/Accounting/Finance" {{ old('industry', $internship->industry) == 'Business/Accounting/Finance' ? 'selected' : '' }}>Business / Finance</option>
                                <option value="Creative/Design" {{ old('industry', $internship->industry) == 'Creative/Design' ? 'selected' : '' }}>Creative / Design</option>
                                <option value="Healthcare/Medical" {{ old('industry', $internship->industry) == 'Healthcare/Medical' ? 'selected' : '' }}>Healthcare</option>
                                <option value="Admin/Human Resource" {{ old('industry', $internship->industry) == 'Admin/Human Resource' ? 'selected' : '' }}>Admin / HR</option>
                            </select>
                            @error('industry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description / Requirements</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="8" required>{{ old('description', $internship->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column - Contact & Location Picker --}}
                    <div class="col-lg-6">
                        <h5 class="fw-bold mb-4 text-purple border-bottom pb-2" style="color: #6f42c1;">Contact Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                                       value="{{ old('contact_person', $internship->contact_person) }}" required>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       value="{{ old('contact_email', $internship->contact_email) }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Contact Phone Number</label>
                            <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   value="{{ old('contact_phone', $internship->contact_phone) }}" required>
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="fw-bold mb-4 mt-4 text-purple border-bottom pb-2" style="color: #6f42c1;">Geographic Location</h5>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Location / Address</label>
                            <div class="input-group">
                                <input type="text" id="location-input" name="location" class="form-control @error('location') is-invalid @enderror" 
                                       value="{{ old('location', $internship->location) }}" required>
                                <button class="btn btn-outline-purple" type="button" onclick="searchLocation()">Search on Map</button>
                            </div>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Leaflet Map Picker --}}
                        <div class="mb-4">
                            <label class="form-label text-muted small">Move the map marker or click on the map to pinpoint coordinates.</label>
                            <div id="map" style="height: 300px; border-radius: 12px; border: 2px solid #e0d4f0; z-index: 1;"></div>
                        </div>

                        {{-- Hidden coordinate fields --}}
                        <input type="hidden" name="lat" id="lat" value="{{ old('lat', $internship->lat ?? '3.1390') }}">
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng', $internship->lng ?? '101.6869') }}">
                    </div>
                </div>

                <div class="border-top pt-4 mt-4">
                    <button type="submit" class="btn btn-success px-5 py-3 shadow" style="background-color: #6f42c1 !important; border-color: #6f42c1 !important; font-weight: 600;">
                        Update Internship Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@yield('header_styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@stack('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;
    
    document.addEventListener('DOMContentLoaded', function () {
        let initialLat = parseFloat(document.getElementById('lat').value) || 3.1390;
        let initialLng = parseFloat(document.getElementById('lng').value) || 101.6869;

        map = L.map('map').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        // Update inputs on drag end
        marker.on('dragend', function (e) {
            let latlng = marker.getLatLng();
            updateCoords(latlng.lat, latlng.lng);
        });

        // Update inputs on map click
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });
    });

    function updateCoords(lat, lng) {
        document.getElementById('lat').value = lat.toFixed(8);
        document.getElementById('lng').value = lng.toFixed(8);
    }

    function searchLocation() {
        let query = document.getElementById('location-input').value;
        if (!query) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    let lat = parseFloat(data[0].lat);
                    let lon = parseFloat(data[0].lon);
                    
                    map.setView([lat, lon], 15);
                    marker.setLatLng([lat, lon]);
                    updateCoords(lat, lon);
                } else {
                    alert('Address not found. Please pinpoint manually on the map.');
                }
            })
            .catch(error => {
                console.error('Error fetching geocoding data:', error);
            });
    }
</script>
