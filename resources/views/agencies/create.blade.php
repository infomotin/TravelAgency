@extends('layouts.app')
@section('content')
<h1 class="h3">Create Agency</h1>
<form method="post" action="{{ route('agencies.store') }}" class="mt-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Currency</label>
        <input name="currency" class="form-control" value="{{ old('currency', 'USD') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active">active</option>
            <option value="inactive">inactive</option>
            <option value="suspended">suspended</option>
        </select>
    </div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection

