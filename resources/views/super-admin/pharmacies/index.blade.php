@extends('layouts.super-admin')

@section('title', 'Pharmacies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pharmacies</h4>
    <a href="{{ route('super-admin.pharmacies.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Pharmacy</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Users</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pharmacies as $pharmacy)
                    <tr>
                        <td>{{ $pharmacy->name }}</td>
                        <td>{{ $pharmacy->owner_name }}</td>
                        <td>{{ $pharmacy->users_count }}</td>
                        <td>
                            <span class="badge {{ $pharmacy->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $pharmacy->status ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td>{{ $pharmacy->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <form action="{{ route('super-admin.pharmacies.toggle-status', $pharmacy) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('{{ $pharmacy->status ? 'Suspend' : 'Reactivate' }} this pharmacy?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $pharmacy->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    {{ $pharmacy->status ? 'Suspend' : 'Reactivate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No pharmacies yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $pharmacies->links() }}</div>
@endsection
