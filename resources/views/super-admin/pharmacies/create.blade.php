@extends('layouts.super-admin')

@section('title', 'Add Pharmacy')

@section('content')
<h4 class="mb-3">Add Pharmacy</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('super-admin.pharmacies.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pharmacy Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Owner Name</label>
                <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Owner Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Owner Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Owner Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('super-admin.pharmacies.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
