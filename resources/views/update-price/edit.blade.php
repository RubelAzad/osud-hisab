@extends('layouts.app')

@section('title', 'Update Price')

@section('content')
<h4 class="mb-3">Update Price</h4>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Search by name..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Search</button>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('update-price.update') }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Medicine</th><th>Category</th><th style="width:150px">Purchase Price</th><th style="width:150px">Sale Price</th></tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->medicine_name }} <span class="text-muted small">{{ $medicine->strength }}</span></td>
                            <td>{{ $medicine->category->name ?? '-' }}</td>
                            <td><input type="number" step="0.01" name="prices[{{ $medicine->id }}][purchase_price]" class="form-control form-control-sm" value="{{ $medicine->purchase_price }}" required></td>
                            <td><input type="number" step="0.01" name="prices[{{ $medicine->id }}][sale_price]" class="form-control form-control-sm" value="{{ $medicine->sale_price }}" required></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Prices</button>
</form>
<div class="mt-3">{{ $medicines->links() }}</div>
@endsection
