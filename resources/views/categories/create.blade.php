@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<h4 class="mb-3">Add Category</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            @include('categories._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('categories.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
