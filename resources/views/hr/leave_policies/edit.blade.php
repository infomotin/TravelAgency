@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Leave Policy</h1>
<form method="post" action="{{ route('leave_policies.update', $policy) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-5">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $policy->name) }}">
    ></div>
    <div class="col-md-3">
        <label class="form-label">Annual Quota</label>
        <input type="number" name="annual_quota" class="form-control" value="{{ old('annual_quota', $policy->annual_quota) }}">
    ></div>
    <div class="col-md-2 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="carry_forward" value="1" id="carry_forward" @if(old('carry_forward', $policy->carry_forward)) checked @endif>
            <label class="form-check-label" for="carry_forward">
                Carry forward
            ></label>
        ></div>
    ></div>
    <div class="col-12">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('leave_policies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    ></div>
></form>
@endsection

