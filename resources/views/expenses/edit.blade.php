@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<h4 class="mb-3">Edit Expense</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('expenses.update', $expense) }}">
            @csrf @method('PUT')
            @include('expenses._form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
