@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Generate Payslip</h1>
<form method="post" action="{{ route('payroll.payslips.store') }}" class="row g-3">
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
        <label class="form-label">Month</label>
        <input type="month" name="month" class="form-control" value="{{ old('month', date('Y-m')) }}">
    ></div>
    <div class="col-12">
        <button class="btn btn-primary">Generate</button>
        <a href="{{ route('payroll.payslips.index') }}" class="btn btn-outline-secondary">Cancel</a>
    ></div>
></form>
@endsection

