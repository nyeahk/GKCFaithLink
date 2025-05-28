@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container py-4">
    <h1>User Details</h1>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name ?? $user->username }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text">
                <strong>Status:</strong>
                @if($user->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Disabled</span>
                @endif
            </p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>
        </div>
    </div>
</div>
@endsection