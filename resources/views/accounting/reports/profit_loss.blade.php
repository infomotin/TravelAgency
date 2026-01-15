@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Profit &amp; Loss</h1>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="form-label">Period</label>
        <select name="period" class="form-select form-select-sm">
            <option value="month" @selected($period === 'month')>This Month</option>
            <option value="year" @selected($period === 'year')>This Year</option>
            <option value="custom" @selected($period === 'custom')>Custom</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary btn-sm w-100">Show</button>
    </div>
</form>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header small fw-semibold">Income</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Account</th>
                            <th class="text-end" style="width: 160px;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($income as $row)
                            <tr>
                                <td>{{ $row['code'] }} - {{ $row['name'] }}</td>
                                <td class="text-end">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-light">
                        <tr>
                            <th class="text-end">Total Income</th>
                            <th class="text-end">{{ number_format($totalIncome, 2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header small fw-semibold">Expenses</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Account</th>
                            <th class="text-end" style="width: 160px;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $row)
                            <tr>
                                <td>{{ $row['code'] }} - {{ $row['name'] }}</td>
                                <td class="text-end">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-light">
                        <tr>
                            <th class="text-end">Total Expenses</th>
                            <th class="text-end">{{ number_format($totalExpenses, 2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div class="fw-semibold">Net {{ $profit >= 0 ? 'Profit' : 'Loss' }}</div>
        <div class="fs-4 {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
            {{ number_format(abs($profit), 2) }}
        </div>
    </div>
</div>
@endsection

