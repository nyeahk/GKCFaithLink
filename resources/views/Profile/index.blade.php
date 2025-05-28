@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>My Profile</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="profile-image-container mb-3">
                                @if(auth()->user()->image_path)
                                    <img src="{{ asset('storage/' . auth()->user()->image_path) }}" alt="{{ auth()->user()->name }}" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                        <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                                    </div>
                                @endif
                            </div>
                            <h4>{{ auth()->user()->name }}</h4>
                            <p class="text-muted">
                                @php
                                    $roleNames = [
                                        1 => 'Administrator',
                                        2 => 'Treasurer',
                                        3 => 'Member',
                                        4 => 'Staff'
                                    ];
                                    $roleName = $roleNames[auth()->user()->role] ?? 'User';
                                @endphp
                                {{ $roleName }}
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                                </a>
                                <a href="{{ route('admin.profile.password') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                    <i class="bi bi-key me-1"></i> Change Password
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Personal Information</h5>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Name:</div>
                                        <div class="col-md-8">{{ auth()->user()->name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Email:</div>
                                        <div class="col-md-8">{{ auth()->user()->email }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Username:</div>
                                        <div class="col-md-8">{{ auth()->user()->username }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Contact Number:</div>
                                        <div class="col-md-8">{{ auth()->user()->contact_number }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Address:</div>
                                        <div class="col-md-8">{{ auth()->user()->address }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Account Created:</div>
                                        <div class="col-md-8">{{ auth()->user()->created_at->format('F d, Y') }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 fw-bold">Last Login:</div>
                                        <div class="col-md-8">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('F d, Y h:i A') : 'Never' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection