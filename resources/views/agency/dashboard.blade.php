@extends('layouts.app')

@section('content')
<div class="row g-4 mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-muted mb-0">Overview for {{ $currentAgency->name }} ({{ $month }})</p>
    </div>
    <div class="col-md-4 text-md-end">
        <form method="get" class="row g-2 justify-content-md-end">
            <div class="col-7">
                <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm">
            </div>
            <div class="col-5">
                <button class="btn btn-sm btn-outline-secondary w-100">Change Month</button>
            </div>
        </form>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted text-uppercase small">Employees</div>
                <div class="display-6 fw-semibold">{{ $employeeCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted text-uppercase small">Tickets</div>
                <div class="display-6 fw-semibold">{{ $ticketCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted text-uppercase small">Approved Payslips</div>
                <div class="display-6 fw-semibold">{{ $approvedPayslips }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted text-uppercase small">Net Profit</div>
                <div class="display-6 fw-semibold">{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Sales This Month</div>
            <div class="card-body">
                <p class="mb-1 text-muted">Total Sales</p>
                <h4>{{ number_format($totalSales, 2) }}</h4>
                <p class="small text-muted mb-0">Based on tickets issued in {{ $month }}.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Quick Links</div>
            <div class="card-body">
                <div class="row g-2">
                    @can('employees.view')
                    <div class="col-6">
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary w-100">Employees</a>
                    </div>
                    @endcan
                    @can('payroll.view')
                    <div class="col-6">
                        <a href="{{ route('payroll.payslips.index') }}" class="btn btn-outline-primary w-100">Payslips</a>
                    </div>
                    @endcan
                    @can('hr_reports.view')
                    <div class="col-6">
                        <a href="{{ route('hr_reports.attendance') }}" class="btn btn-outline-primary w-100">Attendance Report</a>
                    </div>
                    @endcan
                    @can('hr_reports.view')
                    <div class="col-6">
                        <a href="{{ route('hr_reports.leaves') }}" class="btn btn-outline-primary w-100">Leave Report</a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

