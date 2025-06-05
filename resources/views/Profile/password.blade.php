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
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">Back to Profile</a>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
