@extends('layouts.app')

@section('title', 'Add Generic')

@section('content')
<h4 class="mb-3">Add Generic</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('generics.store') }}">
            @csrf
            @include('generics._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('generics.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
