@extends('layouts.app')

@section('title', 'Return Purchase ' . $purchase->invoice_no)

@section('content')
<h4 class="mb-3">Return Items — Purchase {{ $purchase->invoice_no }}</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('purchases.returns.store', $purchase) }}">
    @csrf
    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Return Date</label>
            <input type="date" name="return_date" class="form-control" style="max-width:220px" value="{{ old('return_date', now()->format('Y-m-d')) }}" required>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">Items Purchased</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Medicine</th>
                        <th>Batch No</th>
                        <th>Purchased Qty</th>
                        <th>Remaining Stock</th>
                        <th>Price</th>
                        <th style="width:140px">Return Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $item)
                        <tr>
                            <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                            <td>{{ $item->medicineBatch->batch_no ?? '-' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->medicineBatch->remaining_qty ?? 0 }}</td>
                            <td>{{ number_format($item->purchase_price, 2) }}</td>
                            <td>
                                <input type="hidden" name="items[{{ $loop->index }}][medicine_batch_id]" value="{{ $item->medicine_batch_id }}">
                                <input type="number" name="items[{{ $loop->index }}][qty]" class="form-control form-control-sm" min="0" max="{{ min($item->qty, $item->medicineBatch->remaining_qty ?? 0) }}" value="0">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Process Return</button>
    <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-light mt-3">Cancel</a>
</form>
@endsection
