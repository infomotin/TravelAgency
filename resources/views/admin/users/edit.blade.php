@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit User</h1>
<div class="card">
    <div class="card-body">
        <form method="post" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                </select>
            </div>
            <button class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
    </div>
@endsection

