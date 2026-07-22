@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
<h4 class="mb-3">Edit Location</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('locations.update', $location) }}">
            @csrf @method('PUT')
            @include('locations._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('locations.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
