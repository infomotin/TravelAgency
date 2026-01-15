@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Edit Bill {{ $bill->bill_no }}</h1>
    <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline-secondary btn-sm">Back to bill</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('bills.update', $bill) }}" method="post" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-3">
                <label class="form-label">Bill Date</label>
                <input type="date" name="bill_date" value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" class="form-control form-control-sm @error('bill_date') is-invalid @enderror">
                @error('bill_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', optional($bill->due_date)->format('Y-m-d')) }}" class="form-control form-control-sm @error('due_date') is-invalid @enderror">
                @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Party <span class="text-danger">*</span></label>
                <select name="party_id" class="form-select form-select-sm @error('party_id') is-invalid @enderror" required>
                    <option value="">Select Party</option>
                    @foreach($parties as $party)
                        <option value="{{ $party->id }}" @selected(old('party_id', $bill->party_id) == $party->id)>
                            {{ $party->name }}
                        </option>
                    @endforeach
                </select>
                @error('party_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Reference</label>
                <input type="text" name="reference" value="{{ old('reference', $bill->reference) }}" class="form-control form-control-sm @error('reference') is-invalid @enderror">
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
                
                @if($bill->attachments->count() > 0)
                <div class="mt-2">
                    <div class="small text-muted mb-1">Current Attachments:</div>
                    <ul class="list-group list-group-sm">
                        @foreach($bill->attachments as $attachment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-paperclip me-1"></i> {{ $attachment->file_name }}
                            </a>
                            <button type="submit" form="delete-attachment-{{ $attachment->id }}" class="btn btn-link text-danger p-0 border-0">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label">Lines</label>
                @include('accounting.bills.form-lines')
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary btn-sm">Update Bill</button>
            </div>
        </form>
    </div>
</div>

@foreach($bill->attachments as $attachment)
<form id="delete-attachment-{{ $attachment->id }}" action="{{ route('bill_attachments.destroy', $attachment) }}" method="post" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection

