@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<h4 class="mb-3">Edit Role</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf @method('PUT')
            @include('roles._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
