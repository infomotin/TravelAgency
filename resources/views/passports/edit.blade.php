@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Passport</h1>
<form action="{{ route('passports.update', $passport) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('passports.form', ['passport' => $passport])
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection

