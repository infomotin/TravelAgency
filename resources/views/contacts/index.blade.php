@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Contacts</h1>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createContactModal">
            + Add New Contacts
        </button>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" @if(request('type') === $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Gift Sent</label>
                <select name="sent_gift" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1" @if(request('sent_gift') === '1') selected @endif>Yes</option>
                    <option value="0" @if(request('sent_gift') === '0') selected @endif>No</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Company, contact, mobile">
            </div>
            <div class="col-md-1 d-grid">
                <button class="btn btn-dark btn-sm">Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px;">SL.</th>
                    <th style="width:90px;">Type</th>
                    <th>Company / Airline Name</th>
                    <th>Contact person</th>
                    <th>Designation</th>
                    <th>Mobile</th>
                    <th style="width:80px;">Sent Gift</th>
                    <th>Gift Sent Date</th>
                    <th>Last Gift Name</th>
                    <th>Gift Dates</th>
                    <th style="width:140px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($contacts as $index => $contact)
                    <tr data-contact-id="{{ $contact->id }}">
                        <td>{{ $contacts->firstItem() + $index }}</td>
                        <td class="text-capitalize">{{ $contact->type }}</td>
                        <td>{{ $contact->company_name }}</td>
                        <td>{{ $contact->contact_person }}</td>
                        <td>{{ $contact->designation }}</td>
                        <td>{{ $contact->mobile }}</td>
                        <td data-col="sent_gift">
                            <span class="badge {{ $contact->sent_gift ? 'bg-success' : 'bg-secondary' }}">
                                {{ $contact->sent_gift ? 'YES' : 'NO' }}
                            </span>
                        </td>
                        <td data-col="gift_sent_date">{{ optional($contact->gift_sent_date)->format('d M Y') }}</td>
                        <td data-col="last_gift_name">{{ $contact->last_gift_name }}</td>
                        <td data-col="gift_dates">{{ $contact->gift_dates }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button"
                                        class="btn btn-outline-info btn-view-gifts"
                                        data-bs-toggle="modal"
                                        data-bs-target="#giftsModal"
                                        data-contact-id="{{ $contact->id }}">
                                    Gifts
                                </button>
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editContactModal"
                                        data-id="{{ $contact->id }}"
                                        data-type="{{ $contact->type }}"
                                        data-company_name="{{ $contact->company_name }}"
                                        data-contact_person="{{ $contact->contact_person }}"
                                        data-designation="{{ $contact->designation }}"
                                        data-mobile="{{ $contact->mobile }}"
                                        data-sent_gift="{{ $contact->sent_gift ? 1 : 0 }}"
                                        data-gift_sent_date="{{ optional($contact->gift_sent_date)->format('Y-m-d') }}"
                                        data-last_gift_name="{{ $contact->last_gift_name }}"
                                        data-gift_dates="{{ $contact->gift_dates }}">
                                    Edit
                                </button>
                                <form action="{{ route('contacts.destroy', $contact) }}" method="post" onsubmit="return confirm('Delete this contact?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">No contacts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $contacts->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="giftsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All gift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="giftForm" class="row g-2 mb-3">
                    <input type="hidden" id="giftId">
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" id="giftDate" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gift Name</label>
                        <input type="text" id="giftName" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Remark</label>
                        <input type="text" id="giftRemark" class="form-control">
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="button" class="btn btn-primary btn-sm" id="giftSaveBtn">Save</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="giftResetBtn">Reset</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width:60px;">SL.</th>
                            <th style="width:120px;">Date</th>
                            <th>Gift Name</th>
                            <th>Remark</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="giftTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('contacts.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Company / Airline Name</label>
                            <input type="text" name="company_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact person</label>
                            <input type="text" name="contact_person" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sent Gift</label>
                            <select name="sent_gift" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gift Sent Date</label>
                            <input type="date" name="gift_sent_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Gift Name</label>
                            <input type="text" name="last_gift_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gift Dates (notes)</label>
                            <input type="text" name="gift_dates" class="form-control">
                        </div>
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

<div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editContactForm" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" id="editContactType" class="form-select" required>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Company / Airline Name</label>
                            <input type="text" name="company_name" id="editContactCompany" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact person</label>
                            <input type="text" name="contact_person" id="editContactPerson" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" id="editContactDesignation" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" id="editContactMobile" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sent Gift</label>
                            <select name="sent_gift" id="editContactSentGift" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gift Sent Date</label>
                            <input type="date" name="gift_sent_date" id="editContactGiftDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Gift Name</label>
                            <input type="text" name="last_gift_name" id="editContactLastGift" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gift Dates (notes)</label>
                            <input type="text" name="gift_dates" id="editContactGiftDates" class="form-control">
                        </div>
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
        var editModal = document.getElementById('editContactModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            document.getElementById('editContactType').value = button.getAttribute('data-type') || '';
            document.getElementById('editContactCompany').value = button.getAttribute('data-company_name') || '';
            document.getElementById('editContactPerson').value = button.getAttribute('data-contact_person') || '';
            document.getElementById('editContactDesignation').value = button.getAttribute('data-designation') || '';
            document.getElementById('editContactMobile').value = button.getAttribute('data-mobile') || '';
            document.getElementById('editContactSentGift').value = button.getAttribute('data-sent_gift') || '0';
            document.getElementById('editContactGiftDate').value = button.getAttribute('data-gift_sent_date') || '';
            document.getElementById('editContactLastGift').value = button.getAttribute('data-last_gift_name') || '';
            document.getElementById('editContactGiftDates').value = button.getAttribute('data-gift_dates') || '';

            var form = document.getElementById('editContactForm');
            form.action = '{{ url('contacts') }}/' + id;
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var currentContactId = null;

        $('#giftsModal').on('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            currentContactId = $(button).data('contact-id') || null;
            resetGiftForm();
            loadGifts();
        });

        $('#giftsModal').on('hidden.bs.modal', function () {
            currentContactId = null;
            resetGiftForm();
            $('#giftTableBody').empty();
        });

        $('#giftSaveBtn').on('click', function () {
            if (!currentContactId) {
                return;
            }

            var giftId = $('#giftId').val();
            var url = '{{ url('contacts') }}/' + currentContactId + '/gifts' + (giftId ? '/' + giftId : '');
            var method = giftId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    gift_date: $('#giftDate').val(),
                    gift_name: $('#giftName').val(),
                    remark: $('#giftRemark').val()
                },
                success: function (response) {
                    resetGiftForm();
                    loadGifts();
                    if (response.summary) {
                        updateContactSummary(response.summary);
                    }
                },
                error: function (xhr) {
                    alert('Unable to save gift.');
                }
            });
        });

        $('#giftResetBtn').on('click', function () {
            resetGiftForm();
        });

        $(document).on('click', '.gift-edit', function () {
            var button = $(this);
            $('#giftId').val(button.data('id'));
            $('#giftDate').val(button.data('date'));
            $('#giftName').val(button.data('name'));
            $('#giftRemark').val(button.data('remark'));
        });

        $(document).on('click', '.gift-delete', function () {
            if (!currentContactId) {
                return;
            }

            if (!confirm('Delete this gift?')) {
                return;
            }

            var giftId = $(this).data('id');
            var url = '{{ url('contacts') }}/' + currentContactId + '/gifts/' + giftId;

            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (response) {
                    loadGifts();
                    if (response.summary) {
                        updateContactSummary(response.summary);
                    }
                },
                error: function () {
                    alert('Unable to delete gift.');
                }
            });
        });

        function loadGifts() {
            if (!currentContactId) {
                return;
            }

            var url = '{{ url('contacts') }}/' + currentContactId + '/gifts';

            $.get(url, function (response) {
                var tbody = $('#giftTableBody');
                tbody.empty();

                var items = response.data || [];

                if (!items.length) {
                    return;
                }

                items.forEach(function (gift, index) {
                    var row = $('<tr>');
                    row.append('<td>' + (index + 1) + '</td>');
                    row.append('<td>' + (gift.gift_date_display || '') + '</td>');
                    row.append('<td>' + (gift.gift_name || '') + '</td>');
                    row.append('<td>' + (gift.remark || '') + '</td>');

                    var actions = $('<td>');
                    var editBtn = $('<button type="button" class="btn btn-outline-primary btn-sm me-1 gift-edit">Edit</button>');
                    editBtn.attr('data-id', gift.id);
                    editBtn.attr('data-date', gift.gift_date || '');
                    editBtn.attr('data-name', gift.gift_name || '');
                    editBtn.attr('data-remark', gift.remark || '');

                    var deleteBtn = $('<button type="button" class="btn btn-outline-danger btn-sm gift-delete">Delete</button>');
                    deleteBtn.attr('data-id', gift.id);

                    actions.append(editBtn).append(deleteBtn);
                    row.append(actions);
                    tbody.append(row);
                });
            });
        }

        function resetGiftForm() {
            $('#giftId').val('');
            $('#giftDate').val('');
            $('#giftName').val('');
            $('#giftRemark').val('');
        }

        function updateContactSummary(summary) {
            var row = $('tr[data-contact-id="' + summary.contact_id + '"]');
            if (!row.length) {
                return;
            }

            var sentGiftCell = row.find('td[data-col="sent_gift"]');
            var badgeClass = summary.sent_gift ? 'bg-success' : 'bg-secondary';
            var badgeText = summary.sent_gift ? 'YES' : 'NO';
            sentGiftCell.html('<span class="badge ' + badgeClass + '">' + badgeText + '</span>');

            row.find('td[data-col="gift_sent_date"]').text(summary.gift_sent_date_display || '');
            row.find('td[data-col="last_gift_name"]').text(summary.last_gift_name || '');
            row.find('td[data-col="gift_dates"]').text(summary.gift_dates || '');

            var editButton = row.find('button[data-bs-target="#editContactModal"]');
            editButton.attr('data-sent_gift', summary.sent_gift ? 1 : 0);
            editButton.attr('data-gift_sent_date', summary.gift_sent_date || '');
            editButton.attr('data-last_gift_name', summary.last_gift_name || '');
            editButton.attr('data-gift_dates', summary.gift_dates || '');
        }
    });
</script>
@endsection
