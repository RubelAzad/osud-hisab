@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<h4 class="mb-3">Pharmacy Settings</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pharmacy Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $pharmacy->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $pharmacy->owner_name) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $pharmacy->phone) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $pharmacy->email) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency" class="form-control" value="{{ old('currency', $pharmacy->currency) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Timezone</label>
                    <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $pharmacy->timezone) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">VAT %</label>
                    <input type="number" step="0.01" name="vat_percent" class="form-control" value="{{ old('vat_percent', $pharmacy->vat_percent) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if ($pharmacy->logo)
                        <img src="{{ Storage::url($pharmacy->logo) }}" class="mt-2" style="height:48px;">
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $pharmacy->address) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
@endsection
