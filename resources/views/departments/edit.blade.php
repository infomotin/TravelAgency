@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Department</h1>
<form method="post" action="{{ route('departments.update', $department) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $department->name) }}">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

