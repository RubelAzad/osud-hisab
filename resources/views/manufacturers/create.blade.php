@extends('layouts.app')

@section('title', 'Add Manufacturer')

@section('content')
<h4 class="mb-3">Add Manufacturer</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('manufacturers.store') }}">
            @csrf
            @include('manufacturers._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('manufacturers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
