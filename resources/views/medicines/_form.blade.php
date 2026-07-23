@php $medicine = $medicine ?? null; @endphp
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Medicine Name</label>
        <input type="text" name="medicine_name" class="form-control @error('medicine_name') is-invalid @enderror" value="{{ old('medicine_name', $medicine->medicine_name ?? '') }}" required>
        @error('medicine_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Strength</label>
        <input type="text" name="strength" class="form-control" placeholder="e.g. 500mg" value="{{ old('strength', $medicine->strength ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Barcode</label>
        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" placeholder="Auto-generated if blank" value="{{ old('barcode', $medicine->barcode ?? '') }}">
        @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $medicine->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Manufacturer</label>
        <select name="manufacturer_id" class="form-select @error('manufacturer_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}" {{ old('manufacturer_id', $medicine->manufacturer_id ?? '') == $manufacturer->id ? 'selected' : '' }}>{{ $manufacturer->name }}</option>
            @endforeach
        </select>
        @error('manufacturer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Generic</label>
        <select name="generic_id" class="form-select @error('generic_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($generics as $generic)
                <option value="{{ $generic->id }}" {{ old('generic_id', $medicine->generic_id ?? '') == $generic->id ? 'selected' : '' }}>{{ $generic->name }}</option>
            @endforeach
        </select>
        @error('generic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Medicine Type</label>
        <select name="medicine_type_id" class="form-select @error('medicine_type_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($medicineTypes as $type)
                <option value="{{ $type->id }}" {{ old('medicine_type_id', $medicine->medicine_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
        @error('medicine_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Unit</label>
        <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" {{ old('unit_id', $medicine->unit_id ?? '') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
            @endforeach
        </select>
        @error('unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Minimum Stock</label>
        <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $medicine->minimum_stock ?? 0) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Warranty</label>
        <select name="warranty_id" class="form-select">
            <option value="">None</option>
            @foreach ($warranties as $warranty)
                <option value="{{ $warranty->id }}" {{ old('warranty_id', $medicine->warranty_id ?? '') == $warranty->id ? 'selected' : '' }}>{{ $warranty->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tax Rate</label>
        <select name="tax_rate_id" class="form-select">
            <option value="">None (use VAT % below)</option>
            @foreach ($taxRates as $taxRate)
                <option value="{{ $taxRate->id }}" {{ old('tax_rate_id', $medicine->tax_rate_id ?? '') == $taxRate->id ? 'selected' : '' }}>{{ $taxRate->name }} ({{ $taxRate->rate }}%)</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Purchase Price</label>
        <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $medicine->purchase_price ?? 0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Sale Price</label>
        <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $medicine->sale_price ?? 0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">VAT %</label>
        <input type="number" step="0.01" name="vat" class="form-control" value="{{ old('vat', $medicine->vat ?? 0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $medicine->description ?? '') }}</textarea>
</div>
<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $medicine->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
