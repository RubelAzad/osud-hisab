@extends('layouts.app')

@section('title', 'Edit Expense Category')

@section('content')
<h4 class="mb-3">Edit Expense Category</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('expense-categories.update', $expenseCategory) }}">
            @csrf @method('PUT')
            @include('expense-categories._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('expense-categories.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
