@extends('layouts.app')

@section('title', 'Add Expense Category')

@section('content')
<h4 class="mb-3">Add Expense Category</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('expense-categories.store') }}">
            @csrf
            @include('expense-categories._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('expense-categories.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
