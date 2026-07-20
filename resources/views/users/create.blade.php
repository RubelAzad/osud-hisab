@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<h4 class="mb-3">Add User</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
