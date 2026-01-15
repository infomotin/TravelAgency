@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Monthly Attendance Summary</h1>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="form-label">Month</label>
        <input type="month" name="month" value="{{ $month }}" class="form-control">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100">Apply</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Days</th>
            <th>Late (min)</th>
            <th>Early Leave (min)</th>
            <th>Overtime (min)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{ $row['employee']->employee_code }}</td>
                <td>{{ $row['employee']->name }}</td>
                <td>{{ $row['days'] }}</td>
                <td>{{ $row['late_minutes'] }}</td>
                <td>{{ $row['early_leave_minutes'] }}</td>
                <td>{{ $row['overtime_minutes'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

