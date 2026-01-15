@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-0">{{ $user->name }}</h1>
        <small class="text-muted">User profile</small>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        @can('security.update')
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">Edit Profile</a>
        <a href="{{ route('admin.users.password.edit', $user) }}" class="btn btn-outline-warning btn-sm">Change Password</a>
        @endcan
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Account</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $user->name }}</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>
                    <dt class="col-sm-4">Agency</dt>
                    <dd class="col-sm-8">{{ $user->agency->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $user->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

