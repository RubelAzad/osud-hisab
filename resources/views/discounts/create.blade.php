@extends('layouts.app')

@section('title', 'Add Discount')

@section('content')
<h4 class="mb-3">Add Discount</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('discounts.store') }}">
            @csrf
            @include('discounts._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('discounts.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
