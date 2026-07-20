@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<h4 class="mb-3">Edit Customer</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf @method('PUT')
            @include('customers._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('customers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
