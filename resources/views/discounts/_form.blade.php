<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $discount->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="percentage" {{ old('type', $discount->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
            <option value="fixed" {{ old('type', $discount->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Value</label>
        <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror" min="0" value="{{ old('value', $discount->value ?? '') }}" required>
        @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Applies To</label>
        <select name="applies_to" id="applies_to" class="form-select" onchange="document.getElementById('applies-category').classList.toggle('d-none', this.value !== 'category'); document.getElementById('applies-medicine').classList.toggle('d-none', this.value !== 'medicine');">
            <option value="all" {{ old('applies_to', $discount->applies_to ?? '') === 'all' ? 'selected' : '' }}>All Products</option>
            <option value="category" {{ old('applies_to', $discount->applies_to ?? '') === 'category' ? 'selected' : '' }}>Specific Category</option>
            <option value="medicine" {{ old('applies_to', $discount->applies_to ?? '') === 'medicine' ? 'selected' : '' }}>Specific Medicine</option>
        </select>
    </div>
    <div class="col-md-4 mb-3 {{ old('applies_to', $discount->applies_to ?? '') === 'category' ? '' : 'd-none' }}" id="applies-category">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $discount->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3 {{ old('applies_to', $discount->applies_to ?? '') === 'medicine' ? '' : 'd-none' }}" id="applies-medicine">
        <label class="form-label">Medicine</label>
        <select name="medicine_id" class="form-select">
            <option value="">Select medicine</option>
            @foreach ($medicines as $medicine)
                <option value="{{ $medicine->id }}" {{ old('medicine_id', $discount->medicine_id ?? '') == $medicine->id ? 'selected' : '' }}>{{ $medicine->medicine_name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Starts At</label>
        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', optional($discount->starts_at ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Ends At</label>
        <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at', optional($discount->ends_at ?? null)->format('Y-m-d')) }}">
    </div>
</div>

<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $discount->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
