@extends('layouts.app')

@section('title', 'Add Warranty')

@section('content')
<h4 class="mb-3">Add Warranty</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('warranties.store') }}">
            @csrf
            @include('warranties._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('warranties.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
