@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<h4 class="mb-3">Add Expense</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
            @include('expenses._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
