@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">New Bill</h1>
    <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('bills.store') }}" method="post" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Bill Date</label>
                <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" class="form-control form-control-sm @error('bill_date') is-invalid @enderror">
                @error('bill_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control form-control-sm @error('due_date') is-invalid @enderror">
                @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Party <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                    <select name="party_id" class="form-select @error('party_id') is-invalid @enderror" required>
                        <option value="">Select Party</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" @selected(old('party_id') == $party->id)>
                                {{ $party->name }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('parties.create') }}" class="btn btn-outline-secondary" target="_blank">+</a>
                </div>
                @error('party_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Reference</label>
                <input type="text" name="reference" value="{{ old('reference') }}" class="form-control form-control-sm @error('reference') is-invalid @enderror">
                @error('reference')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Attachments</label>
                <input type="file" name="attachments[]" class="form-control form-control-sm @error('attachments') is-invalid @enderror" multiple>
                @error('attachments')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @foreach($errors->get('attachments.*') as $error)
                    <div class="invalid-feedback d-block">{{ $error[0] }}</div>
                @endforeach
            </div>
            <div class="col-12">
                <label class="form-label">Lines</label>
                @include('accounting.bills.form-lines')
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary btn-sm">Save Bill</button>
            </div>
        </form>
    </div>
@endsection

