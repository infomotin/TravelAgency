@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Salary Structure - {{ $employee->name }}</h1>
<form method="post" action="{{ route('payroll.salary_structures.update', $employee) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-3">
        <label class="form-label">Basic</label>
        <input type="number" step="0.01" name="basic" class="form-control" value="{{ old('basic', $structure->basic ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">House Rent</label>
        <input type="number" step="0.01" name="house_rent" class="form-control" value="{{ old('house_rent', $structure->house_rent ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Medical</label>
        <input type="number" step="0.01" name="medical" class="form-control" value="{{ old('medical', $structure->medical ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Transport</label>
        <input type="number" step="0.01" name="transport" class="form-control" value="{{ old('transport', $structure->transport ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">OT Rate / Hour</label>
        <input type="number" step="0.01" name="overtime_rate_per_hour" class="form-control" value="{{ old('overtime_rate_per_hour', $structure->overtime_rate_per_hour ?? 0) }}">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('payroll.salary_structures.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

