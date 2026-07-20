@extends('layouts.app')

@section('title', 'Generics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Generics</h4>
    @can('generics.create')
        <a href="{{ route('generics.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Generic</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($generics as $generic)
                    <tr>
                        <td>{{ $generic->name }}</td>
                        <td class="text-muted">{{ Str::limit($generic->description, 60) }}</td>
                        <td>{{ $generic->medicines_count }}</td>
                        <td class="text-end">
                            @can('generics.edit')
                                <a href="{{ route('generics.edit', $generic) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('generics.delete')
                                <form action="{{ route('generics.destroy', $generic) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this generic?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No generics yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $generics->links() }}</div>
@endsection
