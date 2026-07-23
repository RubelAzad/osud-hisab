@extends('layouts.app')

@section('title', 'Generics')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-tag text-primary me-2"></i>Generics
        <span class="idx-count">{{ $generics->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('generics.create')
            <a href="{{ route('generics.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Generic</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-end">Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($generics as $generic)
                    <tr>
                        <td class="fw-semibold">{{ $generic->name }}</td>
                        <td class="text-muted-2">{{ Str::limit($generic->description, 50) ?: '-' }}</td>
                        <td class="text-end">{{ $generic->medicines_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('generics.edit')
                                    <a href="{{ route('generics.edit', $generic) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('generics.delete')
                                    <form action="{{ route('generics.destroy', $generic) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this generic?')">
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
                                <i class="bi bi-tag"></i>
                                <p>No generics found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $generics->links() }}
@endsection
