@extends('layouts.app')

@section('title', 'Add Customer Group')

@section('content')
<h4 class="mb-3">Add Customer Group</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customer-groups.store') }}">
            @csrf
            @include('customer-groups._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('customer-groups.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
