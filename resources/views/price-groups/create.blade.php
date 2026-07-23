@extends('layouts.app')

@section('title', 'Add Price Group')

@section('content')
<h4 class="mb-3">Add Price Group</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('price-groups.store') }}">
            @csrf
            @include('price-groups._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('price-groups.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
