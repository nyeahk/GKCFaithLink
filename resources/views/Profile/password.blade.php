@php
    $layout = 'layouts.admin';
    if(auth()->user()->role == 1) {
        $layout = 'layouts.admin';
    } elseif(auth()->user()->role == 2) {
        $layout = 'layouts.treasurer';
    }elseif(auth()->user()->role == 3) {
        $layout = 'layouts.member';
    } elseif(auth()->user()->role == 4) {
        $layout = 'layouts.staff';
    }
@endphp

@extends($layout)

@section('title', 'Change Password')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(auth()->user()->role == 3)
                        <form action="{{ route('member.profile.password.update') }}" method="POST">
                    @elseif(auth()->user()->role == 1)
                        <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @elseif(auth()->user()->role == 2)
                        <form action="{{ route('treasurer.profile.password.update') }}" method="POST">
                    @elseif(auth()->user()->role == 4)
                        <form action="{{ route('staff.profile.password.update') }}" method="POST">
                    @endif
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            @if(auth()->user()->role == 1)
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                                </a>
                            @elseif(auth()->user()->role == 2)
                                <a href="{{ route('treasurer.profile.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                                </a>
                            @elseif(auth()->user()->role == 3)
                                <a href="{{ route('member.profile.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                                </a>
                            @elseif(auth()->user()->role == 4)
                                <a href="{{ route('staff.profile.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center justify-content-center" style="transition: all 0.3s ease;">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary" style="transition: all 0.3s ease;">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Button hover styles */
    .card-body .btn-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #ffffff !important;
    }

    .card-body .btn-secondary:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .card-body .btn-primary {
        background-color: #4F959D !important;
        border-color: #4F959D !important;
        color: #ffffff !important;
    }

    .card-body .btn-primary:hover {
        background-color: #3d7a80 !important;
        border-color: #3d7a80 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Add active state styles */
    .card-body .btn-secondary:active,
    .card-body .btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Override any default Bootstrap styles */
    .btn-secondary:hover,
    .btn-secondary:focus,
    .btn-secondary:active {
        background-color: #5a6268 !important;
        color: #ffffff !important;
        border-color: #5a6268 !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #3d7a80 !important;
        color: #ffffff !important;
        border-color: #3d7a80 !important;
    }

    /* Button hover styles for member panel */
    @if(auth()->user()->role == 3)
        /* Back to Profile button */
        .card-body .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #ffffff !important;
        }

        .card-body .btn-secondary:hover {
            background-color: #495057 !important;  /* Darker gray */
            border-color: #495057 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Update Password button */
        .card-body .btn-primary {
            background-color: #4F959D !important;
            border-color: #4F959D !important;
            color: #ffffff !important;
        }

        .card-body .btn-primary:hover {
            background-color: #2c5a5f !important;  /* Darker teal */
            border-color: #2c5a5f !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Active states */
        .card-body .btn-secondary:active,
        .card-body .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    @endif
</style>
@endpush
