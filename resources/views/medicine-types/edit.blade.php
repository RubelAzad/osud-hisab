@extends('layouts.app')

@section('title', 'Edit Medicine Type')

@section('content')
<h4 class="mb-3">Edit Medicine Type</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medicine-types.update', $medicineType) }}">
            @csrf @method('PUT')
            @include('medicine-types._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('medicine-types.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
