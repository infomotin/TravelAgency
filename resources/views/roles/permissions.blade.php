@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-0">Permissions for Role: {{ $role->name }}</h1>
        <small class="text-muted">Check modules and actions this role is allowed to access.</small>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Back to Roles</a>
    </div>
</div>
<form method="post" action="{{ route('roles.permissions.update', $role) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       id="perm_{{ $permission->id }}"
                                       @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ $permission->name }}
                                    <span class="text-muted d-block small">{{ $permission->slug }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-end">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection
