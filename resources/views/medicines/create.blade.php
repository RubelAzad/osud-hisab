@extends('layouts.app')

@section('title', 'Add Medicine')

@section('content')
<h4 class="mb-3">Add Medicine</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medicines.store') }}" enctype="multipart/form-data">
            @csrf
            @include('medicines._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('medicines.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
