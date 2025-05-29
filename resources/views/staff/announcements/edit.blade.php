@extends('layouts.gkc')

@section('title', 'Edit Announcement')

@section('content')
    <div class="announcements-container">
        <div class="announcements-header">
            <h1><i class="fas fa-edit"></i> Edit Announcement</h1>
            <a href="{{ route('staff.announcements.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Announcements
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <p><strong>Please fix the following errors:</strong></p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="announcement-form-card">
            <form action="{{ route('staff.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data" class="announcement-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="current-image">Current Image</label>
                    @if($announcement->image)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $announcement->image) }}" alt="Current announcement image" class="img-thumbnail">
                        </div>
                    @else
                        <p class="text-muted">No image uploaded</p>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="image">Change Image (Optional)</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="posted_at">Posting Date</label>
                    <input type="datetime-local" name="posted_at" id="posted_at" class="form-control @error('posted_at') is-invalid @enderror" value="{{ old('posted_at', $announcement->posted_at->format('Y-m-d\TH:i')) }}" required>
                    @error('posted_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ old('status', $announcement->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Announcement
                    </button>
                    <a href="{{ route('staff.announcements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .announcements-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .announcements-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .announcements-header h1 {
        margin: 0;
        font-size: 1.75rem;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #edf2f7;
        border-radius: 0.375rem;
        color: #4a5568;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-back:hover {
        background: #e2e8f0;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-success {
        background: #e6fffa;
        color: #2c7a7b;
    }

    .alert-danger {
        background: #fff5f5;
        color: #c53030;
    }

    .alert i {
        margin-top: 0.25rem;
    }

    .announcement-form-card {
        background: white;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .announcement-form {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: grid;
        gap: 0.5rem;
    }

    label {
        font-weight: 500;
        color: #4a5568;
    }

    .form-control {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        width: 100%;
        font-size: 1rem;
        color: #2d3748;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    }

    .is-invalid {
        border-color: #e53e3e;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    textarea {
        resize: vertical;
        min-height: 150px;
    }

    .current-image {
        margin-top: 0.5rem;
    }

    .img-thumbnail {
        max-width: 200px;
        height: auto;
        border-radius: 0.25rem;
        border: 1px solid #e2e8f0;
    }

    .text-muted {
        color: #718096;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        border: none;
    }

    .btn-primary {
        background: #4299e1;
        color: white;
    }

    .btn-primary:hover {
        background: #3182ce;
    }

    .btn-secondary {
        background: #edf2f7;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    @media (max-width: 768px) {
        .announcements-container {
            padding: 1rem;
        }

        .announcements-header {
            flex-direction: