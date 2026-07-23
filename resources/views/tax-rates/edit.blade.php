@extends('layouts.app')

@section('title', 'Edit Tax Rate')

@section('content')
<h4 class="mb-3">Edit Tax Rate</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tax-rates.update', $taxRate) }}">
            @csrf @method('PUT')
            @include('tax-rates._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('tax-rates.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
