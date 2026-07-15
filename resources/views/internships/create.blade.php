@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>Add Internship</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('internships.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="internship_name">Internship Name</label>
            <input type="text" name="internship_name" value="{{ old('internship_name') }}" placeholder="Internship Name" class="form-control" required>
            @error('internship_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" name="company" value="{{ old('company') }}" placeholder="Company" class="form-control" required>
            @error('company')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="industry">Industry</label>
            <select name="industry" class="form-control" required>
                <option value="">Select Industry</option>
                <option value="IT/Information Technology" {{ old('industry') == 'IT/Information Technology' ? 'selected' : '' }}>IT/Information Technology</option>
                <option value="Engineering" {{ old('industry') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                <option value="Business/Accounting/Finance" {{ old('industry') == 'Business/Accounting/Finance' ? 'selected' : '' }}>Business/Accounting/Finance</option>
                <option value="Healthcare/Medical" {{ old('industry') == 'Healthcare/Medical' ? 'selected' : '' }}>Healthcare/Medical</option>
                <option value="Build/Architecture/Construction" {{ old('industry') == 'Build/Architecture/Construction' ? 'selected' : '' }}>Build/Architecture/Construction</option>
                <option value="Creative/Design" {{ old('industry') == 'Creative/Design' ? 'selected' : '' }}>Creative/Design</option>
                <option value="Admin/Human Resource" {{ old('industry') == 'Admin/Human Resource' ? 'selected' : '' }}>Admin/Human Resource</option>
            </select>
            @error('industry')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="Location" class="form-control" required>
            @error('location')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>



        <div class="mb-3">
            <label class="form-label">Apply Link URL</label>
            <input type="url"
                name="source_url"
                class="form-control"
                value="{{ old('source_url') }}"
                placeholder="https://my.hiredly.com/jobs/...">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" placeholder="Description" class="form-control" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group form-check mb-4 mt-3">
            <input type="checkbox" name="is_closed" class="form-check-input" id="is_closed" {{ old('is_closed') ? 'checked' : '' }}>
            <label class="form-check-label text-danger fw-bold" for="is_closed">Mark as Closed/Unavailable</label>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('internships.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
