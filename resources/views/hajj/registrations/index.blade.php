@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Hajj Registration Invoices</h1>
    <a href="{{ route('hajj.registrations.create') }}" class="btn btn-primary">Create</a>
</div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 60px;">SL</th>
                    <th style="width: 130px;">Invoice No</th>
                    <th style="width: 110px;">Sales Date</th>
                    <th>Client Name</th>
                    <th style="width: 140px;" class="text-end">Sales Price</th>
                    <th style="width: 140px;" class="text-end">Rec Amount</th>
                    <th style="width: 140px;" class="text-end">Due Amount</th>
                    <th style="width: 130px;">MR No</th>
                    <th style="width: 160px;">Sales By</th>
                    <th style="width: 150px;" class="text-end">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($registrations as $registration)
                    <tr>
                        <td>{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                        <td>{{ $registration->invoice_no }}</td>
                        <td>{{ optional($registration->sales_date)->format('Y-m-d') }}</td>
                        <td>{{ $registration->client->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($registration->net_total, 2) }}</td>
                        <td class="text-end">{{ number_format(($registration->payment_amount ?? 0) + ($registration->payment_discount ?? 0), 2) }}</td>
                        <td class="text-end">
                            @php $due = $registration->invoice_due ?? 0; @endphp
                            <span class="{{ $due > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($due, 2) }}
                            </span>
                        </td>
                        <td>{{ $registration->receipt_no }}</td>
                        <td>{{ $registration->employee->name ?? '' }}</td>
                        <td class="text-end">
                            <span class="text-muted small">-</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted small">No Hajj registrations found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $registrations->links() }}
@endsection
