@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Edit Account</h1>
    <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary btn-sm">Back to Chart</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('accounts.update', $account) }}" method="post" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <label class="form-label">Code</label>
                <input type="text" name="code" value="{{ old('code', $account->code) }}" class="form-control form-control-sm @error('code') is-invalid @enderror">
                @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-8">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $account->name) }}" class="form-control form-control-sm @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror">
                    @foreach(['asset','liability','equity','income','expense'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $account->type) === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Parent Account</label>
                <select name="parent_id" class="form-select form-select-sm @error('parent_id') is-invalid @enderror">
                    <option value="">None</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id', $account->parent_id) == $parent->id)>
                            {{ $parent->code }} - {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Opening Balance</label>
                <input type="number" step="0.01" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}" class="form-control form-control-sm @error('opening_balance') is-invalid @enderror">
                @error('opening_balance')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description', $account->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary btn-sm">Update Account</button>
            </div>
        </form>
    </div>
</div>
@endsection

