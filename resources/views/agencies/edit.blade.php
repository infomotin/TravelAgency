@extends('layouts.app')
@section('content')
<h1 class="h3">Edit Agency</h1>
<form method="post" action="{{ route('agencies.update', $agency) }}" class="mt-3">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $agency->name) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug', $agency->slug) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Currency</label>
        <input name="currency" class="form-control" value="{{ old('currency', $agency->currency) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" @if($agency->status==='active') selected @endif>active</option>
            <option value="inactive" @if($agency->status==='inactive') selected @endif>inactive</option>
            <option value="suspended" @if($agency->status==='suspended') selected @endif>suspended</option>
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
</form>
@endsection

