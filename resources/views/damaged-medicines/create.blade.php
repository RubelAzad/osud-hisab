@extends('layouts.app')

@section('title', 'Record Damaged Medicine')

@section('content')
<h4 class="mb-3">Record Damaged Medicine</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('damaged-medicines.store') }}">
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
                <label class="form-label">Quantity Damaged</label>
                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" min="1" value="{{ old('qty', 1) }}" required>
                @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="2">{{ old('reason') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('damaged-medicines.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
