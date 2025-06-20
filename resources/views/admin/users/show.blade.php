@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container py-4">
    <h1>User Details</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="profile-image-container mb-3">
                        @if($user->image_path)
                            <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->name }}" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                            </div>
                        @endif
                    </div>
                    <h4>{{ $user->name ?? $user->username }}</h4>
                    <p class="text-muted">
                        @php
                            $roleNames = [
                                1 => 'Administrator',
                                2 => 'Treasurer',
                                3 => 'Member',
                                4 => 'Staff'
                            ];
                            $roleName = $roleNames[$user->role] ?? 'User';
                        @endphp
                        {{ $roleName }}
                    </p>
                    <p class="text-muted">
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Disabled</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-8">
                    <h5 class="card-title">Personal Information</h5>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Username:</div>
                        <div class="col-md-8">{{ $user->username }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>
                    @if($user->name)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Name:</div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>
                    @endif
                    @if($user->contact_number)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Contact Number:</div>
                        <div class="col-md-8">{{ $user->contact_number }}</div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Address:</div>
                        <div class="col-md-8">{{ $user->address }}</div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Created:</div>
                        <div class="col-md-8">{{ $user->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Last Updated:</div>
                        <div class="col-md-8">{{ $user->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="mt-4 d-flex">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.users.index') }}" class="btn btn-secondary me-2 rounded-pill d-flex align-items-center justify-content-center" style="height: 38px;">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }} rounded-pill d-flex align-items-center justify-content-center" style="height: 38px;">
                                <i class="bi {{ $user->is_active ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>
                                {{ $user->is_active ? 'Disable' : 'Enable' }} User
                            </button>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle rounded-pill d-flex align-items-center justify-content-center" type="button" id="roleDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="height: 38px;">
                                <i class="bi bi-person-gear me-1"></i> Change Role
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="roleDropdown">
                                <form action="{{ route('admin.users.assignRole', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <li><button type="submit" name="role" value="1" class="dropdown-item {{ $user->role == 1 ? 'active' : '' }}">Administrator</button></li>
                                    <li><button type="submit" name="role" value="2" class="dropdown-item {{ $user->role == 2 ? 'active' : '' }}">Treasurer</button></li>
                                    <li><button type="submit" name="role" value="3" class="dropdown-item {{ $user->role == 3 ? 'active' : '' }}">Member</button></li>
                                    <li><button type="submit" name="role" value="4" class="dropdown-item {{ $user->role == 4 ? 'active' : '' }}">Staff</button></li>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Push the current page into history
    history.pushState(null, '', location.href);

    window.addEventListener('popstate', function(event) {
        // When the user tries to go back, redirect to dashboard
        window.location.href = '/dashboard';  // Adjust this URL to your actual dashboard route
    });
</script>
@endsection