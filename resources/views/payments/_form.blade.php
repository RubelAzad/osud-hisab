<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select">
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="mobile_banking">Mobile Banking</option>
            <option value="bank">Bank</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Transaction No.</label>
    <input type="text" name="transaction_no" class="form-control" value="{{ old('transaction_no') }}">
</div>
<div class="mb-3">
    <label class="form-label">Note</label>
    <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
</div>
