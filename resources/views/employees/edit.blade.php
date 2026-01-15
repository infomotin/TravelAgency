@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Edit Employee</h1>

<form method="post" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="official-tab" data-bs-toggle="tab" data-bs-target="#official" type="button" role="tab">Official Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">Personal Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab">Emergency Contact</button>
        </li>
    </ul>

    <div class="tab-content" id="employeeTabsContent">
        
        <!-- Official Info Tab -->
        <div class="tab-pane fade show active" id="official" role="tabpanel">
            <div class="card p-3 border-top-0 rounded-0 rounded-bottom">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code) }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" @if(old('status', $employee->status)==='active') selected @endif>Active</option>
                            <option value="inactive" @if(old('status', $employee->status)==='inactive') selected @endif>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if(old('department_id', $employee->department_id)==$department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" @if(old('designation_id', $employee->designation_id)==$designation->id) selected @endif>{{ $designation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Shift</label>
                        <select name="shift_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" @if(old('shift_id', $employee->shift_id)==$shift->id) selected @endif>{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', optional($employee->joining_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Probation End Date</label>
                        <input type="date" name="probation_end_date" class="form-control" value="{{ old('probation_end_date', optional($employee->probation_end_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Profile Photo</label>
                        @if($employee->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo" class="rounded-circle" width="50" height="50">
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Info Tab -->
        <div class="tab-pane fade" id="personal" role="tabpanel">
            <div class="card p-3 border-top-0 rounded-0 rounded-bottom">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Father's Name</label>
                        <input name="father_name" class="form-control" value="{{ old('father_name', $employee->father_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mother's Name</label>
                        <input name="mother_name" class="form-control" value="{{ old('mother_name', $employee->mother_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob', optional($employee->dob)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" @if(old('gender', $employee->gender)=='Male') selected @endif>Male</option>
                            <option value="Female" @if(old('gender', $employee->gender)=='Female') selected @endif>Female</option>
                            <option value="Other" @if(old('gender', $employee->gender)=='Other') selected @endif>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Marital Status</label>
                        <select name="marital_status" class="form-select">
                            <option value="">Select</option>
                            <option value="Single" @if(old('marital_status', $employee->marital_status)=='Single') selected @endif>Single</option>
                            <option value="Married" @if(old('marital_status', $employee->marital_status)=='Married') selected @endif>Married</option>
                            <option value="Divorced" @if(old('marital_status', $employee->marital_status)=='Divorced') selected @endif>Divorced</option>
                            <option value="Widowed" @if(old('marital_status', $employee->marital_status)=='Widowed') selected @endif>Widowed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            <option value="A+" @if(old('blood_group', $employee->blood_group)=='A+') selected @endif>A+</option>
                            <option value="A-" @if(old('blood_group', $employee->blood_group)=='A-') selected @endif>A-</option>
                            <option value="B+" @if(old('blood_group', $employee->blood_group)=='B+') selected @endif>B+</option>
                            <option value="B-" @if(old('blood_group', $employee->blood_group)=='B-') selected @endif>B-</option>
                            <option value="AB+" @if(old('blood_group', $employee->blood_group)=='AB+') selected @endif>AB+</option>
                            <option value="AB-" @if(old('blood_group', $employee->blood_group)=='AB-') selected @endif>AB-</option>
                            <option value="O+" @if(old('blood_group', $employee->blood_group)=='O+') selected @endif>O+</option>
                            <option value="O-" @if(old('blood_group', $employee->blood_group)=='O-') selected @endif>O-</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">National ID (NID)</label>
                        <input name="nid" class="form-control" value="{{ old('nid', $employee->nid) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone (Personal)</label>
                        <input name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email (Personal)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Present Address</label>
                        <textarea name="present_address" class="form-control" rows="2">{{ old('present_address', $employee->present_address) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Permanent Address</label>
                        <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact Tab -->
        <div class="tab-pane fade" id="emergency" role="tabpanel">
            <div class="card p-3 border-top-0 rounded-0 rounded-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Contact Name</label>
                        <input name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Phone</label>
                        <input name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Relation</label>
                        <input name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-3">
        <button class="btn btn-primary">Update Employee</button>
        <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>

</form>
@endsection
