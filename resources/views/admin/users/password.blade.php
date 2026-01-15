@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Change Password</h1>
<div class="card">
    <div class="card-body">
        <form method="post" action="{{ route('admin.users.password.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button class="btn btn-warning">Update Password</button>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

