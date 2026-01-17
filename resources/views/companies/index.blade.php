@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Non-Invoice Companies</h1>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
        + Add New Company
    </button>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px;">SL.</th>
                    <th>Company Name</th>
                    <th>Address</th>
                    <th>Contact Person</th>
                    <th>Designation</th>
                    <th>Phone</th>
                    <th style="width:130px;">Create Date</th>
                    <th style="width:140px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($companies as $index => $company)
                    <tr>
                        <td>{{ $companies->firstItem() + $index }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->contact_person }}</td>
                        <td>{{ $company->designation }}</td>
                        <td>{{ $company->phone }}</td>
                        <td>{{ optional($company->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCompanyModal"
                                        data-id="{{ $company->id }}"
                                        data-name="{{ $company->name }}"
                                        data-address="{{ $company->address }}"
                                        data-contact_person="{{ $company->contact_person }}"
                                        data-designation="{{ $company->designation }}"
                                        data-phone="{{ $company->phone }}">
                                    Edit
                                </button>
                                <form action="{{ route('companies.destroy', $company) }}" method="post" onsubmit="return confirm('Delete this company?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No companies found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $companies->links() }}
    </div>
</div>

<div class="modal fade" id="createCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('companies.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
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

<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCompanyForm" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="name" id="editCompanyName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" id="editCompanyAddress" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" id="editCompanyContactPerson" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" id="editCompanyDesignation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="editCompanyPhone" class="form-control">
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
        var editModal = document.getElementById('editCompanyModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            document.getElementById('editCompanyName').value = button.getAttribute('data-name') || '';
            document.getElementById('editCompanyAddress').value = button.getAttribute('data-address') || '';
            document.getElementById('editCompanyContactPerson').value = button.getAttribute('data-contact_person') || '';
            document.getElementById('editCompanyDesignation').value = button.getAttribute('data-designation') || '';
            document.getElementById('editCompanyPhone').value = button.getAttribute('data-phone') || '';

            var form = document.getElementById('editCompanyForm');
            form.action = '{{ url('companies') }}/' + id;
        });
    });
</script>
@endsection

