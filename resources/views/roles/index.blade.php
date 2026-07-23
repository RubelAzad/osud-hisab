@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-shield-lock text-primary me-2"></i>Roles
        <span class="idx-count">{{ $roles->count() }}</span>
    </h4>
    <div class="idx-actions">
        @can('roles.create')
            <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Role</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Permissions</th>
                    <th class="text-end">Users</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td class="fw-semibold">{{ $role->name }}</td>
                        <td class="text-end">{{ $role->permissions()->count() }}</td>
                        <td class="text-end">{{ $role->users_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('roles.edit')
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('roles.delete')
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="idx-empty">
                                <i class="bi bi-shield-lock"></i>
                                <p>No roles found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
