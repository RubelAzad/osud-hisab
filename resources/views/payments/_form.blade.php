<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-select" onchange="document.getElementById('cheque-fields').classList.toggle('d-none', this.value !== 'cheque')">
            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            <option value="mobile_banking" {{ old('payment_method') === 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>
            <option value="bank" {{ old('payment_method') === 'bank' ? 'selected' : '' }}>Bank</option>
            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
    </div>
</div>
<div id="cheque-fields" class="row {{ old('payment_method') === 'cheque' ? '' : 'd-none' }}">
    <div class="col-md-4 mb-3">
        <label class="form-label">Cheque No.</label>
        <input type="text" name="cheque_no" class="form-control @error('cheque_no') is-invalid @enderror" value="{{ old('cheque_no') }}">
        @error('cheque_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Bank Name</label>
        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label">Cheque Date</label>
        <input type="date" name="cheque_date" class="form-control @error('cheque_date') is-invalid @enderror" value="{{ old('cheque_date') }}">
        @error('cheque_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
