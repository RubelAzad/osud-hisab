@extends('layouts.app')

@section('title', 'Add Tax Rate')

@section('content')
<h4 class="mb-3">Add Tax Rate</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tax-rates.store') }}">
            @csrf
            @include('tax-rates._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tax-rates.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
