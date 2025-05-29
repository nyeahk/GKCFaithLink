@extends('layouts.gkc')

@section('title', 'Edit Event')

@section('content')
<div class="edit-event-container">
    <div class="edit-event-header">
        <div class="header-content">
            <h1><i class="fas fa-edit"></i> Edit Event</h1>
            <p class="subtitle">Update the event details below</p>
        </div>
        <a href="{{ route('staff.events.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Events
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

    <div class="edit-event-form">
        <form action="{{ route('staff.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="event-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $event->location) }}" required>
                @error('location')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                @error('start_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">End Date</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
                @error('end_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Event Image</label>
                @if($event->image_path)
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="img-thumbnail">
                        <p>Current image</p>
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                <small class="form-text text-muted">Leave empty to keep the current image</small>
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Event
                </button>
                <a href="{{ route('staff.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
