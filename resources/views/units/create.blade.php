@extends('layouts.app')

@section('title', 'Add Unit')

@section('content')
<h4 class="mb-3">Add Unit</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('units.store') }}">
            @csrf
            @include('units._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('units.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
