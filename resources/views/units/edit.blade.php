@extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')
<h4 class="mb-3">Edit Unit</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('units.update', $unit) }}">
            @csrf @method('PUT')
            @include('units._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('units.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
