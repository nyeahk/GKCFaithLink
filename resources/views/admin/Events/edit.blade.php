@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<div class="events-container">
    <div class="events-header">
        <div class="header-content">
            <h1>Edit Event</h1>
            <p class="subtitle">Update the event details below</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Events
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="event-form-card">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="event-form">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-input @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required>
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-textarea @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-input @error('start_date') is-invalid @enderror" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                    @error('start_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-input @error('end_date') is-invalid @enderror" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
                    @error('end_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-input @error('location') is-invalid @enderror" value="{{ old('location', $event->location) }}" required>
                    @error('location')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Current Image</label>
                    @if($event->image_path)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="Current event image" class="image-preview">
                        </div>
                    @else
                        <p class="text-muted">No image uploaded</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Change Image (Optional)</label>
                    <input type="file" name="image" id="image" class="form-input @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.events-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.events-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: #1a365d;
    margin: 0;
    font-size: 1.875rem;
}

.subtitle {
    color: #4a5568;
    margin: 0.5rem 0 0;
}

.btn-back {
    display: inline-flex;
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

.btn-back:hover {
    background-color: #e2e8f0;
}

.event-form-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.event-form {
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

.current-image {
    margin-bottom: 1rem;
}

.image-preview {
    max-width: 300px;
    border-radius: 0.5rem;
    border: 2px solid #e2e8f0;
}

.text-muted {
    color: #718096;
    font-style: italic;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background-color: #3182ce;
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

.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #f0fff4;
    color: #2f855a;
    border: 1px solid #c6f6d5;
}

.alert i {
    font-size: 1.25rem;
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