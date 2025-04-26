@extends('layouts.gkc')

@section('title', 'Edit Member')

@section('content')
<div class="members-container">
    <div class="members-header">
        <div class="header-content">
            <h1>Edit Member</h1>
            <p class="subtitle">Update the member's information below</p>
        </div>
        <a href="{{ route('members.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Members
        </a>
    </div>

    <div class="member-form-card">
        <form action="{{ route('members.update', $member) }}" method="POST" class="member-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $member->first_name) }}" 
                        class="form-input @error('first_name') is-invalid @enderror" required>
                    @error('first_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $member->last_name) }}"
                        class="form-input @error('last_name') is-invalid @enderror" required>
                    @error('last_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}"
                        class="form-input @error('email') is-invalid @enderror" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $member->phone) }}"
                        class="form-input @error('phone') is-invalid @enderror">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="form-textarea @error('address') is-invalid @enderror" required>{{ old('address', $member->address) }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Profile Picture</label>
                    @if($member->image_path)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $member->image_path) }}" alt="Current Profile Picture" class="profile-preview">
                        </div>
                    @endif
                    <input type="file" id="image" name="image" class="form-input @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="membership_type" class="form-label">Membership Type</label>
                    <select name="membership_type" id="membership_type"
                        class="form-select @error('membership_type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="regular" {{ old('membership_type', $member->membership_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="associate" {{ old('membership_type', $member->membership_type) == 'associate' ? 'selected' : '' }}>Associate</option>
                        <option value="visitor" {{ old('membership_type', $member->membership_type) == 'visitor' ? 'selected' : '' }}>Visitor</option>
                    </select>
                    @error('membership_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status"
                        class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('status', $member->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Member
                </button>
                <a href="{{ route('members.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.members-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: #1a365d;
    margin: 0;
}

.subtitle {
    color: #4a5568;
    margin: 0.5rem 0 0;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #f7fafc;
    color: #4a5568;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.back-btn:hover {
    background-color: #e2e8f0;
}

.member-form-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.member-form {
    max-width: 800px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-weight: 500;
    color: #1a365d;
}

.form-input,
.form-textarea,
.form-select {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #2b6cb0;
    box-shadow: 0 0 0 2px #bee3f8;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.error-message {
    color: #c53030;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background-color: #2b6cb0;
    color: white;
}

.btn-primary:hover {
    background-color: #2c5282;
}

.btn-secondary {
    background-color: #f7fafc;
    color: #4a5568;
}

.btn-secondary:hover {
    background-color: #e2e8f0;
}

.current-image {
    margin-bottom: 1rem;
}

.profile-preview {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
}

.is-invalid {
    border-color: #c53030;
}

.is-invalid:focus {
    border-color: #c53030;
    box-shadow: 0 0 0 2px #fed7d7;
}
</style>
@endsection 