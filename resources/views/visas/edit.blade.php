@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Visa</h1>
<form action="{{ route('visas.update', $visa) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('visas.form')
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('visas.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection

