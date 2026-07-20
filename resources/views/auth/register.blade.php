@extends('layouts.guest')

@section('title', 'Register your pharmacy')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="pharmacy_name" class="form-label">Pharmacy Name</label>
            <input type="text" class="form-control" id="pharmacy_name" name="pharmacy_name" value="{{ old('pharmacy_name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="owner_name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Create my pharmacy account</button>
    </form>
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small">Already have an account? Log in</a>
    </div>
@endsection
