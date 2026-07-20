@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
<h4 class="mb-3">Add Customer</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            @include('customers._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('customers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
