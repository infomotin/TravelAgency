@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Passport Status</h1>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPassportStatusModal">
        + Add New Passport Status
    </button>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px;">SL.</th>
                    <th>Status</th>
                    <th>Create Date</th>
                    <th style="width:160px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($statuses as $index => $status)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $status->name }}</td>
                        <td>{{ optional($status->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPassportStatusModal"
                                        data-id="{{ $status->id }}"
                                        data-name="{{ $status->name }}"
                                        data-status="{{ $status->status }}">
                                    Edit
                                </button>
                                <form action="{{ route('passport_statuses.destroy', $status) }}" method="post" onsubmit="return confirm('Delete this passport status?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No passport statuses found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createPassportStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('passport_statuses.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Passport Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" name="name" class="form-control" required placeholder="RECEIVED, APPROVED, PENDING, DELIVERED">
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

<div class="modal fade" id="editPassportStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPassportStatusForm" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Passport Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" name="name" id="editPassportStatusName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <select name="status" id="editPassportStatusState" class="form-select">
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
        var editModal = document.getElementById('editPassportStatusModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var state = button.getAttribute('data-status') || 'active';

            document.getElementById('editPassportStatusName').value = name;
            document.getElementById('editPassportStatusState').value = state;

            var form = document.getElementById('editPassportStatusForm');
            form.action = '{{ url('passport-statuses') }}/' + id;
        });
    });
</script>
@endsection

