@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Role</h1>
<form method="post" action="{{ route('roles.update', $role) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-4">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $role->name) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ $role->slug }}" disabled>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

