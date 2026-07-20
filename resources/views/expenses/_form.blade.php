@php $expense = $expense ?? null; @endphp
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <select name="expense_category_id" class="form-select @error('expense_category_id') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('expense_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount ?? '') }}" required>
        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense?->expense_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $expense->description ?? '') }}</textarea>
</div>
