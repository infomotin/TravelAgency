@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Add Permission</h1>
<form method="post" action="{{ route('permissions.store') }}" class="row g-3">
    @csrf
    <div class="col-md-4">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug') }}">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

