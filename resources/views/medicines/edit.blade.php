@extends('layouts.app')

@section('title', 'Edit Medicine')

@section('content')
<h4 class="mb-3">Edit Medicine</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medicines.update', $medicine) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('medicines._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('medicines.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
