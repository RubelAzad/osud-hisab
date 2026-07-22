@extends('layouts.app')

@section('title', 'Add Location')

@section('content')
<h4 class="mb-3">Add Location</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('locations.store') }}">
            @csrf
            @include('locations._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('locations.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
