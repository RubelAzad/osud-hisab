@extends('layouts.app')

@section('title', 'Add Draft')

@section('content')
<h4 class="mb-3">Add Draft</h4>

@include('quotations._form', [
    'formAction' => route('drafts.store'),
    'submitLabel' => 'Save Draft',
    'cancelRoute' => route('drafts.index'),
])
@endsection
