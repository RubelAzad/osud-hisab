<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Opening Balance</label>
        @if (isset($supplier))
            <input type="text" class="form-control" value="{{ number_format($supplier->opening_balance, 2) }}" disabled>
        @else
            <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}">
        @endif
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address ?? '') }}</textarea>
</div>
<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $supplier->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
