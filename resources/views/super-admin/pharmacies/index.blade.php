@extends('layouts.super-admin')

@section('title', 'Pharmacies')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-capsule text-primary me-2"></i>Pharmacies
        <span class="idx-count">{{ $pharmacies->total() }}</span>
    </h4>
    <div class="idx-actions">
        <a href="{{ route('super-admin.pharmacies.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Pharmacy</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Owner</th>
                    <th class="text-end">Users</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pharmacies as $pharmacy)
                    <tr>
                        <td class="fw-semibold">{{ $pharmacy->name }}</td>
                        <td>{{ $pharmacy->owner_name }}</td>
                        <td class="text-end">{{ $pharmacy->users_count }}</td>
                        <td>
                            <span class="badge {{ $pharmacy->status ? 'badge-active' : 'badge-danger' }}">
                                {{ $pharmacy->status ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td>{{ $pharmacy->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                <form action="{{ route('super-admin.pharmacies.toggle-status', $pharmacy) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ $pharmacy->status ? 'Suspend' : 'Reactivate' }} this pharmacy?')">
                                    @csrf @method('PATCH')
                                    <button class="btn {{ $pharmacy->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        {{ $pharmacy->status ? 'Suspend' : 'Reactivate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-capsule"></i>
                                <p>No pharmacies found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $pharmacies->links() }}
@endsection
