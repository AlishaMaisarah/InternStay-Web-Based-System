@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>Internship Details</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $internship->internship_name }}</h5>
            <p class="card-text"><strong>Company:</strong> {{ $internship->company }}</p>
            <p class="card-text"><strong>Industry:</strong> {{ $internship->industry }}</p>
            <p class="card-text"><strong>Location:</strong> {{ $internship->location }}</p>

            <div class="card mt-3">
                <div class="card-body">
                    {!! nl2br(e($internship->description)) !!}
                </div>
            </div>

        </div>
    </div>

    <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
    <a href="{{ route('internships.edit', $internship->id) }}" class="btn btn-primary">Edit</a>
</div>
@endsection
