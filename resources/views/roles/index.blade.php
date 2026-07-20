@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Roles</h4>
    @can('roles.create')
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Role</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th>Users</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions()->count() }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td class="text-end">
                            @can('roles.edit')
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('roles.delete')
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No roles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
