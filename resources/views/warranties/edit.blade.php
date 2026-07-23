@extends('layouts.app')

@section('title', 'Edit Warranty')

@section('content')
<h4 class="mb-3">Edit Warranty</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('warranties.update', $warranty) }}">
            @csrf @method('PUT')
            @include('warranties._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('warranties.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
