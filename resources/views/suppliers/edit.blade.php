@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<h4 class="mb-3">Edit Supplier</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf @method('PUT')
            @include('suppliers._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
