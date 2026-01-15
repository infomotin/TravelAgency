@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Designation</h1>
<form method="post" action="{{ route('designations.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
    ></div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('designations.index') }}" class="btn btn-outline-secondary">Cancel</a>
    ></div>
></form>
@endsection

