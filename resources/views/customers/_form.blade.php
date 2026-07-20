<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Opening Balance</label>
        @if (isset($customer))
            <input type="text" class="form-control" value="{{ number_format($customer->opening_balance, 2) }}" disabled>
        @else
            <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}">
        @endif
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
</div>
