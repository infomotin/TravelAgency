@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Holiday</h1>
<form method="post" action="{{ route('holidays.update', $holiday) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" name="date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}" class="form-control form-control-sm @error('date') is-invalid @enderror">
        @error('date')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    ></div>
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $holiday->name) }}" class="form-control form-control-sm @error('name') is-invalid @enderror">
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    ></div>
    <div class="col-12">
        <button class="btn btn-primary btn-sm">Update</button>
        <a href="{{ route('holidays.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
    ></div>
></form>
@endsection

