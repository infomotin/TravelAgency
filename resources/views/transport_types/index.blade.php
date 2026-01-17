@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Transport Types</h1>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTransportTypeModal">
        + Add New Transport Type
    </button>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px;">SL.</th>
                    <th>Transport Type Name</th>
                    <th style="width:140px;">Status</th>
                    <th style="width:160px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transportTypes as $index => $type)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $type->name }}</td>
                        <td>
                            <span class="badge {{ $type->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($type->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTransportTypeModal"
                                        data-id="{{ $type->id }}"
                                        data-name="{{ $type->name }}"
                                        data-status="{{ $type->status }}">
                                    Edit
                                </button>
                                <form action="{{ route('transport_types.destroy', $type) }}" method="post" onsubmit="return confirm('Delete this transport type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No transport types found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createTransportTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transport_types.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Transport Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transport Type Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="AC, Bus, Car, Rickshaw">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editTransportTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editTransportTypeForm" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transport Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transport Type Name</label>
                        <input type="text" name="name" id="editTransportTypeName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editTransportTypeStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editTransportTypeModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var status = button.getAttribute('data-status') || 'active';

            document.getElementById('editTransportTypeName').value = name;
            document.getElementById('editTransportTypeStatus').value = status;

            var form = document.getElementById('editTransportTypeForm');
            form.action = '{{ url('transport-types') }}/' + id;
        });
    });
</script>
@endsection

