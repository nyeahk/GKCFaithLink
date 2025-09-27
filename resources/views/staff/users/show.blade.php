@extends('layouts.staff')

@section('title', 'User Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">User Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('staff.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">{{ $user->getFullNameAttribute() }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person me-1"></i>
                    User Information
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Full Name:</div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-muted me-2"></i>
                                <div>
                                    <div class="fw-medium">{{ $user->getFullNameAttribute() }}</div>
                                    @if($user->username)
                                        <small class="text-muted">@{{ $user->username }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email:</div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Role:</div>
                        <div class="col-md-9">
                            @switch($user->role)
                                @case(1)
                                    <span class="badge bg-primary">Admin</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-info">Treasurer</span>
                                    @break
                                @case(3)
                                    <span class="badge bg-success">Member</span>
                                    @break
                                @case(4)
                                    <span class="badge bg-secondary">Staff</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">Unknown</span>
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status:</div>
                        <div class="col-md-9">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    
                    
                    @if($user->contact_number)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Contact Number:</div>
                        <div class="col-md-9">{{ $user->contact_number }}</div>
                    </div>
                    @endif
                    
                    @if($user->address)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Address:</div>
                        <div class="col-md-9">{{ $user->address }}</div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created:</div>
                        <div class="col-md-9">{{ $user->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Last Updated:</div>
                        <div class="col-md-9">{{ $user->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('staff.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection