@extends('layouts.app')
@section('content')
<h1 class="h3">Profile Setting</h1>
<form method="post" action="{{ route('agencies.update', $agency) }}" class="mt-3" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header">Change Logo</div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        @if($agency->logo_path)
                            <img src="{{ asset('storage/'.$agency->logo_path) }}" alt="Logo" class="img-fluid rounded" style="max-height: 160px;">
                        @else
                            <div class="text-muted small">No logo uploaded</div>
                        @endif
                    </div>
                    <input type="file" name="logo" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header">Organization Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Agency Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $agency->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input name="profile_email" class="form-control" value="{{ old('profile_email', $agency->settings['profile_email'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile No</label>
                        <input name="profile_mobile" class="form-control" value="{{ old('profile_mobile', $agency->settings['profile_mobile'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input name="profile_full_name" class="form-control" value="{{ old('profile_full_name', $agency->settings['profile_full_name'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address 1</label>
                        <input name="address" class="form-control" value="{{ old('address', $agency->address) }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <input name="currency" class="form-control" value="{{ old('currency', $agency->currency) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" @if($agency->status==='active') selected @endif>active</option>
                                    <option value="inactive" @if($agency->status==='inactive') selected @endif>inactive</option>
                                    <option value="suspended" @if($agency->status==='suspended') selected @endif>suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input name="slug" class="form-control" value="{{ old('slug', $agency->slug) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">Extra Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Address 2</label>
                        <input name="profile_address2" class="form-control" value="{{ old('profile_address2', $agency->settings['profile_address2'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extra Info</label>
                        <input name="profile_extra_info" class="form-control" value="{{ old('profile_extra_info', $agency->settings['profile_extra_info'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Facebook</label>
                        <input name="profile_facebook" class="form-control" value="{{ old('profile_facebook', $agency->settings['profile_facebook'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input name="profile_website" class="form-control" value="{{ old('profile_website', $agency->settings['profile_website'] ?? '') }}">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
