@extends('layouts.app')

@section('title', 'Edit Customer Group')

@section('content')
<h4 class="mb-3">Edit Customer Group</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customer-groups.update', $customerGroup) }}">
            @csrf @method('PUT')
            @include('customer-groups._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('customer-groups.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
