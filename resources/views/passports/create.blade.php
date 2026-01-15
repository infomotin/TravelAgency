@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Passport</h1>
<form action="{{ route('passports.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('passports.form', ['passport' => new \App\Models\Passport()])
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection

