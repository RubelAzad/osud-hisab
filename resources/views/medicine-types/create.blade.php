@extends('layouts.app')

@section('title', 'Add Medicine Type')

@section('content')
<h4 class="mb-3">Add Medicine Type</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medicine-types.store') }}">
            @csrf
            @include('medicine-types._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('medicine-types.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
