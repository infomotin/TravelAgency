@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Admin Dashboard - {{ $currentAgency->name ?? '' }}</h1>
    <form method="get" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 me-2">Month</label>
        <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" style="width:auto;">
        <button class="btn btn-sm btn-outline-secondary">Apply</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <div class="card-title h6 mb-1">Employees</div>
                <div class="display-6">{{ $employeeCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
            <div class="card-title h6 mb-1">Tickets</div>
            <div class="display-6">{{ $ticketCount }}</div>
          </div>
        </div>
      </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <div class="card-title h6 mb-1">Approved Payslips ({{ $month }})</div>
                <div class="display-6">{{ $approvedPayslips }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <div class="card-title h6 mb-1">Ticket Profit ({{ $month }})</div>
                <div class="display-6">{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Quick Links
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <a href="{{ route('agencies.index') }}" class="btn btn-outline-primary w-100">Agencies</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-primary w-100">Employees</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('departments.index') }}" class="btn btn-outline-primary w-100">HR Setup</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary w-100">Air Ticket</a>
            </div>
        </div>
    </div>
</div>
@endsection
