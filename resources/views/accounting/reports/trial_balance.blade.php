@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Trial Balance</h1>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <label class="form-label">As of date</label>
        <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary btn-sm w-100">Show</button>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 120px;">Code</th>
                    <th>Account</th>
                    <th style="width: 160px;" class="text-end">Debit</th>
                    <th style="width: 160px;" class="text-end">Credit</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @foreach($data as $row)
                    @php
                        $totalDebit += $row['debit'];
                        $totalCredit += $row['credit'];
                    @endphp
                    <tr>
                        <td>{{ $row['code'] }}</td>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-end">{{ number_format($row['debit'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['credit'], 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end">Total</th>
                    <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                    <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

