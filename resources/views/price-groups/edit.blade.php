@extends('layouts.app')

@section('title', 'Edit Price Group')

@section('content')
<h4 class="mb-3">Edit Price Group</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('price-groups.update', $priceGroup) }}">
            @csrf @method('PUT')
            @include('price-groups._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('price-groups.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
