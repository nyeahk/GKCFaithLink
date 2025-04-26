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
                    <label for="image" class="form-label">Profile Picture</label>
                    <div class="image-upload-container">
                        <input type="file" id="image" name="image" class="form-input" accept="image/*" onchange="previewImage(this)">
                        <div class="image-preview" id="imagePreview">
                            @if(auth()->user()->image_path)
                                <img src="{{ asset('storage/' . auth()->user()->image_path) }}" alt="Current profile picture">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-user"></i>
                                    <span>No image selected</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-input">
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-input">
                    @error('new_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.profile-container {
    padding: 2rem;
}

.profile-header {
    margin-bottom: 2rem;
}

.profile-header h1 {
    color: #1a365d;
    margin: 0;
}

.profile-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
}

.profile-info {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profile-avatar {
    text-align: center;
    margin-bottom: 2rem;
}

.avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #bee3f8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 3rem;
    color: #2b6cb0;
}

.profile-avatar h2 {
    margin: 0;
    color: #1a365d;
}

.role {
    color: #4a5568;
    margin: 0.5rem 0 0;
}

.profile-details {
    margin-top: 2rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.label {
    color: #4a5568;
}

.value {
    color: #1a365d;
    font-weight: 500;
}

.profile-form {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profile-form h2 {
    color: #1a365d;
    margin: 0 0 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #4a5568;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 1rem;
}

.form-input:focus {
    outline: none;
    border-color: #2b6cb0;
    box-shadow: 0 0 0 2px #bee3f8;
}

.error-message {
    color: #c53030;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: block;
}

.form-actions {
    margin-top: 2rem;
}

.btn-submit {
    background: #2b6cb0;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-submit:hover {
    background: #2c5282;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 2rem;
}

.alert-success {
    background: #c6f6d5;
    color: #2f855a;
    border: 1px solid #48bb78;
}

@media (max-width: 768px) {
    .profile-content {
        grid-template-columns: 1fr;
    }
}

.image-upload-container {
    margin-bottom: 1rem;
}

.image-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    margin: 1rem auto;
    border: 2px solid #e2e8f0;
    background: #f7fafc;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #4a5568;
}

.no-image i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.no-image span {
    font-size: 0.875rem;
}

.profile-update-form {
    max-width: 600px;
    margin: 0 auto;
}
</style>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection 