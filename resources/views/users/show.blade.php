@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>User Details</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="card-text"><strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
</div>
@endsection