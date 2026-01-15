@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3">{{ $agency->name }}</h1>
    <a href="{{ route('agencies.edit', $agency) }}" class="btn btn-primary">Edit</a>
</div>
<dl class="row mt-3">
    <dt class="col-sm-3">Slug</dt><dd class="col-sm-9">{{ $agency->slug }}</dd>
    <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $agency->status }}</dd>
    <dt class="col-sm-3">Currency</dt><dd class="col-sm-9">{{ $agency->currency }}</dd>
</dl>
@endsection

