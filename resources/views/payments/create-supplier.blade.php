@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<h4 class="mb-3">Record Payment — {{ $supplier->name }}</h4>
<p class="text-muted">Current balance due: {{ number_format($supplier->balance, 2) }}</p>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.payments.store', $supplier) }}">
            @csrf
            @include('payments._form')
            <button type="submit" class="btn btn-primary">Save Payment</button>
            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
