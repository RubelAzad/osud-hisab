@extends('layouts.app')

@section('title', 'Add Role')

@section('content')
<h4 class="mb-3">Add Role</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            @include('roles._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
