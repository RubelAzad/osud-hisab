@php $user = $user ?? null; @endphp
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Select role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ old('role', $user?->roles->first()?->name) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Password {{ $user ? '(leave blank to keep unchanged)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $user ? '' : 'required' }}>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-check form-switch mb-3">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $user->status ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Active</label>
</div>
