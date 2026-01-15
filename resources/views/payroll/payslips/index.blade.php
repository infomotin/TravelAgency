@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Payslips</h1>
    @can('payroll.create')
    <a href="{{ route('payroll.payslips.create') }}" class="btn btn-primary">Generate Payslip</a>
    @endcan
</div>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="form-label">Month</label>
        <input type="month" name="month" class="form-control" value="{{ $month }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100">Filter</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Month</th>
            <th>Gross</th>
            <th>Deductions</th>
            <th>OT</th>
            <th>Net</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($payslips as $payslip)
            <tr>
                <td>{{ $payslip->employee->name ?? '' }}</td>
                <td>{{ $payslip->month }}</td>
                <td>{{ number_format($payslip->gross, 2) }}</td>
                <td>{{ number_format($payslip->deductions, 2) }}</td>
                <td>{{ number_format($payslip->overtime_amount, 2) }}</td>
                <td>{{ number_format($payslip->net, 2) }}</td>
                <td>{{ ucfirst($payslip->status) }}</td>
                <td class="text-end">
                    @if($payslip->status === 'draft')
                        @can('payroll.update')
                        <form action="{{ route('payroll.payslips.approve', $payslip) }}" method="post" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-success">Approve</button>
                        </form>
                        @endcan
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $payslips->links() }}
@endsection
