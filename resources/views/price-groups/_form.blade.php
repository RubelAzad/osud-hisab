<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $priceGroup->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $priceGroup->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
