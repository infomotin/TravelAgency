@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Shift</h1>
<form method="post" action="{{ route('shifts.store') }}" class="row g-3">
    @csrf
    <div class="col-md-4">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
    ></div>
    <div class="col-md-3">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
    ></div>
    <div class="col-md-3">
        <label class="form-label">End Time</label>
        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
    ></div>
    <div class="col-md-2">
        <label class="form-label">Grace Minutes</label>
        <input type="number" name="grace_minutes" class="form-control" value="{{ old('grace_minutes', 0) }}">
    ></div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('shifts.index') }}" class="btn btn-outline-secondary">Cancel</a>
    ></div>
></form>
@endsection

