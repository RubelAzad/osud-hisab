<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $taxRate->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Rate (%)</label>
    <input type="number" step="0.01" name="rate" class="form-control @error('rate') is-invalid @enderror" min="0" max="100" value="{{ old('rate', $taxRate->rate ?? '') }}" required>
    @error('rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $taxRate->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
