@php $rolePermissions = $rolePermissions ?? []; @endphp
<div class="mb-3">
    <label class="form-label">Role Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<label class="form-label">Permissions</label>
<div class="row">
    @foreach ($permissions as $module => $modulePermissions)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white py-2 fw-semibold text-capitalize">{{ str_replace('_', ' ', $module) }}</div>
                <div class="card-body py-2">
                    @foreach ($modulePermissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="perm-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm-{{ $permission->id }}">{{ substr($permission->name, strpos($permission->name, '.') + 1) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
