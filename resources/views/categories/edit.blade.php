@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<h4 class="mb-3">Edit Category</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf @method('PUT')
            @include('categories._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('categories.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
