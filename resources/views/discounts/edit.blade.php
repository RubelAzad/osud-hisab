@extends('layouts.app')

@section('title', 'Edit Discount')

@section('content')
<h4 class="mb-3">Edit Discount</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('discounts.update', $discount) }}">
            @csrf @method('PUT')
            @include('discounts._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('discounts.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
