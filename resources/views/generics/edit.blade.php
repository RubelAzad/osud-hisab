@extends('layouts.app')

@section('title', 'Edit Generic')

@section('content')
<h4 class="mb-3">Edit Generic</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('generics.update', $generic) }}">
            @csrf @method('PUT')
            @include('generics._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('generics.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
