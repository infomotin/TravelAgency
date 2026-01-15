@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $employee->name }} <span class="text-muted h5">({{ $employee->employee_code }})</span></h1>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit Profile</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Profile Photo & ID Card -->
    <div class="col-md-4">
        <!-- Profile Photo Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded-circle mb-3 border border-4 border-light shadow-sm" width="150" height="150" style="object-fit: cover;">
                @else
                    <div class="rounded-circle mb-3 bg-secondary d-inline-flex align-items-center justify-content-center text-white" style="width: 150px; height: 150px; font-size: 3rem;">
                        {{ substr($employee->name, 0, 1) }}
                    </div>
                @endif
                <h5 class="card-title">{{ $employee->name }}</h5>
                <p class="text-muted mb-1">{{ $employee->designation->name ?? 'No Designation' }}</p>
                <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($employee->status) }}</span>
            </div>
        </div>

        <!-- ID Card Preview -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">ID Card</h5>
            </div>
            <div class="card-body d-flex justify-content-center bg-light p-4">
                <div class="id-card bg-white shadow-sm overflow-hidden position-relative" style="width: 320px; height: 200px; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <!-- Header -->
                    <div class="d-flex align-items-center px-3 py-2" style="background: linear-gradient(to right, #1e293b, #334155);">
                        <div class="text-white fw-bold small">TRAVEL AGENCY</div>
                        <div class="ms-auto text-white-50 small">ID: {{ $employee->employee_code }}</div>
                    </div>
                    
                    <div class="d-flex p-3 align-items-center h-100 pb-5">
                        <div class="me-3">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded border" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px;">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="lh-1">
                            <h6 class="fw-bold mb-1 text-dark">{{ $employee->name }}</h6>
                            <p class="small text-muted mb-1">{{ $employee->designation->name ?? 'Staff' }}</p>
                            <p class="small text-muted mb-1" style="font-size: 0.75rem;">{{ $employee->department->name ?? '' }}</p>
                            <div class="mt-2 text-danger fw-bold" style="font-size: 0.7rem;">BLOOD: {{ $employee->blood_group ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <!-- Footer Bar -->
                    <div class="position-absolute bottom-0 w-100 py-1 text-center bg-light border-top" style="font-size: 0.65rem; color: #64748b;">
                        Issue Date: {{ now()->format('Y-m-d') }}
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-center">
                <button class="btn btn-sm btn-outline-dark" onclick="printIdCard()">Print ID Card</button>
            </div>
        </div>
    </div>

    <!-- Right Column: Details -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#official-info">Official Info</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#personal-info">Personal Info</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#emergency-info">Emergency Contact</button>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                
                <!-- Official Info -->
                <div class="tab-pane fade show active" id="official-info">
                    <h6 class="text-uppercase text-muted mb-3 small fw-bold">Employment Details</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Employee Code</label>
                            <span class="fw-medium">{{ $employee->employee_code }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Department</label>
                            <span class="fw-medium">{{ $employee->department->name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Designation</label>
                            <span class="fw-medium">{{ $employee->designation->name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Shift</label>
                            <span class="fw-medium">{{ $employee->shift->name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Joining Date</label>
                            <span class="fw-medium">{{ optional($employee->joining_date)->format('M d, Y') ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Probation End Date</label>
                            <span class="fw-medium">{{ optional($employee->probation_end_date)->format('M d, Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="tab-pane fade" id="personal-info">
                    <h6 class="text-uppercase text-muted mb-3 small fw-bold">Basic Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Father's Name</label>
                            <span class="fw-medium">{{ $employee->father_name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Mother's Name</label>
                            <span class="fw-medium">{{ $employee->mother_name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Date of Birth</label>
                            <span class="fw-medium">{{ optional($employee->dob)->format('M d, Y') ?? '-' }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Gender</label>
                            <span class="fw-medium">{{ $employee->gender ?? '-' }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Blood Group</label>
                            <span class="fw-medium">{{ $employee->blood_group ?? '-' }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Marital Status</label>
                            <span class="fw-medium">{{ $employee->marital_status ?? '-' }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">NID</label>
                            <span class="fw-medium">{{ $employee->nid ?? '-' }}</span>
                        </div>
                    </div>

                    <h6 class="text-uppercase text-muted mb-3 small fw-bold border-top pt-3">Contact Information</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Phone</label>
                            <span class="fw-medium">{{ $employee->phone ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Email</label>
                            <span class="fw-medium">{{ $employee->email ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block">Present Address</label>
                            <span class="fw-medium">{{ $employee->present_address ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block">Permanent Address</label>
                            <span class="fw-medium">{{ $employee->permanent_address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="tab-pane fade" id="emergency-info">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Contact Name</label>
                            <span class="fw-medium">{{ $employee->emergency_contact_name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Relationship</label>
                            <span class="fw-medium">{{ $employee->emergency_contact_relation ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Phone Number</label>
                            <span class="fw-medium">{{ $employee->emergency_contact_phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function printIdCard() {
    // A simple print implementation for the ID card part
    var printContent = document.querySelector('.id-card').outerHTML;
    var originalContent = document.body.innerHTML;
    
    // Create a temporary window for printing
    var win = window.open('', '', 'width=800,height=600');
    win.document.write('<html><head><title>Print ID Card</title>');
    win.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
    win.document.write('</head><body class="d-flex justify-content-center align-items-center vh-100">');
    win.document.write(printContent);
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    setTimeout(() => {
        win.print();
        win.close();
    }, 500);
}
</script>
@endsection
