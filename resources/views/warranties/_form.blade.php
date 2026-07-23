<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $warranty->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Duration (days)</label>
    <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" min="1" value="{{ old('duration_days', $warranty->duration_days ?? '') }}" required>
    @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $warranty->description ?? '') }}</textarea>
</div>
