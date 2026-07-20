@extends('layouts.app')

@section('title', 'Add Cash Account')

@section('content')
<h4 class="mb-3">Add Cash Account</h4>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('cash-accounts.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Account Name</label>
                <input type="text" name="account_name" class="form-control" placeholder="e.g. Bank, Mobile Banking" value="{{ old('account_name') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('cash-accounts.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
