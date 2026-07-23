@extends('layouts.app')

@section('title', 'Set Prices — '.$priceGroup->name)

@section('content')
<h4 class="mb-3">Set Prices — {{ $priceGroup->name }}</h4>

<form method="POST" action="{{ route('price-groups.prices.update', $priceGroup) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Medicine</th><th>Base Sale Price</th><th style="width:180px">{{ $priceGroup->name }} Price</th></tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->medicine_name }} <span class="text-muted small">{{ $medicine->strength }}</span></td>
                            <td>{{ number_format($medicine->sale_price, 2) }}</td>
                            <td>
                                <input type="number" step="0.01" name="prices[{{ $medicine->id }}]" class="form-control form-control-sm"
                                    value="{{ $medicine->priceGroups->first()?->pivot->price }}" placeholder="Same as base">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Prices</button>
    <a href="{{ route('price-groups.index') }}" class="btn btn-light mt-3">Cancel</a>
</form>
@endsection
