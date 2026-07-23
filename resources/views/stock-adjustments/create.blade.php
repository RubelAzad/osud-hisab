@extends('layouts.app')

@section('title', 'Adjust Stock')

@section('content')
<h4 class="mb-3">Adjust Stock</h4>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('stock-adjustments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Batch</label>
                <select name="medicine_batch_id" class="form-select @error('medicine_batch_id') is-invalid @enderror" required>
                    <option value="">Select batch</option>
                    @foreach ($medicines as $medicine)
                        @foreach ($medicine->batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('medicine_batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $medicine->medicine_name }} — Batch {{ $batch->batch_no }} ({{ $batch->remaining_qty }} in stock)
                            </option>
                        @endforeach
                    @endforeach
                </select>
                @error('medicine_batch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Adjustment Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="increase" {{ old('type') === 'increase' ? 'selected' : '' }}>Increase</option>
                    <option value="decrease" {{ old('type') === 'decrease' ? 'selected' : '' }}>Decrease</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" min="1" value="{{ old('qty', 1) }}" required>
                @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="2">{{ old('reason') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('stock-adjustments.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
