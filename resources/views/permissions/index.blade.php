@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">Permissions Catalog</h1>
        <small class="text-muted">Define fine grained access (create, read, update, delete) for modules.</small>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        @can('security.create')
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">New Permission</a>
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
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td><span class="badge text-bg-secondary">{{ $permission->slug }}</span></td>
                        <td class="text-end">
                            @can('security.update')
                            <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endcan
                            @can('security.delete')
                            <form action="{{ route('permissions.destroy', $permission) }}" method="post" class="d-inline ms-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this permission?')">Delete</button>
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
