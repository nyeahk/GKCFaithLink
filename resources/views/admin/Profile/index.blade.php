@extends('layouts.gkc')

@section('title', 'My Profile')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1>My Profile</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-content">
        <div class="profile-info">
            <div class="profile-avatar">
                @if(auth()->user()->image_path)
                    <img src="{{ asset('storage/' . auth()->user()->image_path) }}" alt="{{ auth()->user()->name }}" class="avatar-image">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h2>{{ auth()->user()->name }}</h2>
                <p class="role">{{ auth()->user()->role }}</p>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <span class="label">Email:</span>
                    <span class="value">{{ auth()->user()->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Last Login:</span>
                    <span class="value">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y h:i A') : 'Never' }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Account Created:</span>
                    <span class="value">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="profile-form">
            <h2>Update Profile</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-update-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Profile Image</label>
                    <input type="file" id="image" name="image" class="form-control-file">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control">
                    <small class="form-text text-muted">Required only if changing password</small>
                </div>
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                    <small class="form-text text-muted">Leave empty to keep current password</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

