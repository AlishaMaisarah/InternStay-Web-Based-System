@extends('layouts.sidebar')

@section('content')
<div class="container">
    <h2>Add Property</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rentals.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="property_name">Property Name</label>
            <input type="text" name="property_name" value="{{ old('property_name') }}" placeholder="Property Name" class="form-control" required>
            @error('property_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="property_type">Property Type</label>
            <select name="property_type" class="form-control" required>
                <option value="">Select Property Type</option>
                <option value="Apartment" {{ old('property_type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="House" {{ old('property_type') == 'House' ? 'selected' : '' }}>House</option>
                <option value="Condominium" {{ old('property_type') == 'Condominium' ? 'selected' : '' }}>Condominium</option>
                <!--<option value="Townhouse" {{ old('property_type') == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>-->
                <option value="Single Room" {{ old('property_type') == 'Single Room' ? 'selected' : '' }}>Single Room</option>
                <option value="Shared Room" {{ old('property_type') == 'Shared Room' ? 'selected' : '' }}>Shared Room</option>
                <option value="Studio" {{ old('property_type') == 'Studio' ? 'selected' : '' }}>Studio</option>
            </select>
            @error('property_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" placeholder="Full Address" class="form-control" required>{{ old('address') }}</textarea>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-group">
            <label for="rent_amount">Rent Amount (MYR)</label>
            <input type="number" name="rent_amount" value="{{ old('rent_amount') }}" step="0.01" placeholder="0.00" class="form-control" required>
            @error('rent_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="bedrooms">Bedrooms</label>
            <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" placeholder="Number of bedrooms" class="form-control">
            @error('bedrooms')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="bathrooms">Bathrooms</label>
            <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" placeholder="Number of bathrooms" class="form-control">
            @error('bathrooms')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" placeholder="Property description" class="form-control" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    

        <div class="form-group">
            <label for="is_available">Available</label>
            <select name="is_available" class="form-control">
                <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('is_available')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('rentals.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection