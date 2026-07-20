@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')
<h4 class="mb-3">Add Supplier</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            @include('suppliers._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
