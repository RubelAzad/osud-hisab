@extends('layouts.app')

@section('title', 'Edit Manufacturer')

@section('content')
<h4 class="mb-3">Edit Manufacturer</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('manufacturers.update', $manufacturer) }}">
            @csrf @method('PUT')
            @include('manufacturers._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('manufacturers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
