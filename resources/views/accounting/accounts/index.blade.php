@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Chart of Accounts</h1>
    @can('accounts.create')
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">Add Account</a>
    @endcan
</div>

@if(session('success'))
    <div class="alert alert-success small">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger small">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 120px;">Code</th>
                    <th>Name</th>
                    <th style="width: 120px;">Type</th>
                    <th style="width: 160px;" class="text-end">Opening Balance</th>
                    <th style="width: 140px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    @include('accounting.accounts.partials.row', ['account' => $account, 'depth' => 0])
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted small">
        Showing chart of accounts for {{ app('currentAgency')->name ?? 'Agency' }}
    </div>
</div>
@endsection

