<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $unit->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Short Name</label>
        <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $unit->short_name ?? '') }}" required>
        @error('short_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
