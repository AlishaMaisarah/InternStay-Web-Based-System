@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>Edit Internship</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('internships.update', $internship->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="internship_name">Internship Name</label>
            <input type="text" name="internship_name" value="{{ old('internship_name', $internship->internship_name) }}" class="form-control" required>
            @error('internship_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" name="company" value="{{ old('company', $internship->company) }}" class="form-control" required>
            @error('company')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="industry">Industry</label>
            <select name="industry" class="form-control" required>
                <option value="">Select Industry</option>
                <option value="IT/Information Technology" {{ old('industry', $internship->industry) == 'IT/Information Technology' ? 'selected' : '' }}>IT/Information Technology</option>
                <option value="Engineering" {{ old('industry', $internship->industry) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                <option value="Business/Accounting/Finance" {{ old('industry', $internship->industry) == 'Business/Accounting/Finance' ? 'selected' : '' }}>Business/Accounting/Finance</option>
                <option value="Healthcare/Medical" {{ old('industry', $internship->industry) == 'Healthcare/Medical' ? 'selected' : '' }}>Healthcare/Medical</option>
                <option value="Build/Architecture/Construction" {{ old('industry', $internship->industry) == 'Build/Architecture/Construction' ? 'selected' : '' }}>Build/Architecture/Construction</option>
                <option value="Creative/Design" {{ old('industry', $internship->industry) == 'Creative/Design' ? 'selected' : '' }}>Creative/Design</option>
                <option value="Admin/Human Resource" {{ old('industry', $internship->industry) == 'Admin/Human Resource' ? 'selected' : '' }}>Admin/Human Resource</option>
            </select>
            @error('industry')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" value="{{ old('location', $internship->location) }}" class="form-control" required>
            @error('location')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>



        <div class="mb-3">
            <label class="form-label">Apply Link URL</label>
            <input type="url" name="source_url" class="form-control"
                value="{{ old('source_url', $internship->source_url) }}"
                placeholder="https://my.hiredly.com/jobs/....">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $internship->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group form-check mb-4 mt-3">
            <input type="checkbox" name="is_closed" class="form-check-input" id="is_closed" {{ old('is_closed', $internship->is_closed) ? 'checked' : '' }}>
            <label class="form-check-label text-danger fw-bold" for="is_closed">Mark as Closed/Unavailable</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('internships.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
