@extends('layouts.app')

@section('title', 'Add Quotation')

@section('content')
<h4 class="mb-3">Add Quotation</h4>

@include('quotations._form', [
    'formAction' => route('quotations.store'),
    'submitLabel' => 'Save Quotation',
    'cancelRoute' => route('quotations.index'),
])
@endsection
