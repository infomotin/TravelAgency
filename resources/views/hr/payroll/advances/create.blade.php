@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Salary Advance</h1>
<form method="post" action="{{ route('payroll.advances.store') }}" class="row g-3">
    @csrf
    <div class="col-md-4">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select">
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        ></select>
    ></div>
    <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
    ></div>
    <div class="col-md-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
    ></div>
    <div class="col-md-6">
        <label class="form-label">Note</label>
        <input name="note" class="form-control" value="{{ old('note') }}">
    ></div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('payroll.advances.index') }}" class="btn btn-outline-secondary">Cancel</a>
    ></div>
></form>
@endsection

