@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Edit Transaction</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('transactions.update', $transaction) }}" method="post" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" class="form-control form-control-sm @error('date') is-invalid @enderror">
                @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror">
                    @foreach(['payment','receipt','journal','contra'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $transaction->type) === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Reference</label>
                <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}" class="form-control form-control-sm @error('reference') is-invalid @enderror">
                @error('reference')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description', $transaction->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Lines</label>
                @include('accounting.transactions.form-lines')
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary btn-sm">Update Transaction</button>
            </div>
        </form>
    </div>
</div>
@endsection

