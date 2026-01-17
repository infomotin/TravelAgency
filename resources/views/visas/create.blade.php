@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Visa</h1>
<form action="{{ route('visas.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('visas.form', ['visa' => new \App\Models\Visa()])
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('visas.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
