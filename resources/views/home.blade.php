{{-- Not used. Kept only for compatibility. --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <p>This page is deprecated. Redirecting to admin dashboard...</p>
</div>

<script>
    window.location.href = "/admin";
</script>
@endsection
