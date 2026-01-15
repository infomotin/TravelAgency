@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Users for Role: {{ $role->name }}</h1>
<form method="post" action="{{ route('roles.users.update', $role) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-12">
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="users[]" value="{{ $user->id }}"
                               id="user_{{ $user->id }}"
                               @if(in_array($user->id, $roleUsers)) checked @endif>
                        <label class="form-check-label" for="user_{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

