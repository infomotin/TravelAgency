@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">Roles & Access Control</h1>
        <small class="text-muted">Manage role based user access and permissions.</small>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        @can('security.create')
        <a href="{{ route('roles.create') }}" class="btn btn-primary">New Role</a>
        @endcan
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Users</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $role->name }}</div>
                        </td>
                        <td><span class="badge text-bg-secondary">{{ $role->slug }}</span></td>
                        <td><span class="badge text-bg-info">{{ $role->users_count }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                @can('security.update')
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary">Edit</a>
                                <a href="{{ route('roles.permissions.edit', $role) }}" class="btn btn-outline-secondary">Permissions</a>
                                <a href="{{ route('roles.users.edit', $role) }}" class="btn btn-outline-secondary">Users</a>
                                @endcan
                            </div>
                            @can('security.delete')
                            <form action="{{ route('roles.destroy', $role) }}" method="post" class="d-inline ms-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

