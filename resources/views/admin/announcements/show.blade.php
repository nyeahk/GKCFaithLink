@extends('layouts.gkc')

@section('title', 'View Announcement')

@section('content')
<div class="announcements-container">
    <div class="announcements-header">
        <div class="header-content">
            <h1>View Announcement</h1>
            <p class="subtitle">View and manage announcement details</p>
        </div>
        <a href="{{ route('announcements.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Announcements
        </a>
    </div>

    <div class="announcement-card">
        <div class="announcement-header">
            <h2>{{ $announcement->title }}</h2>
            <div class="announcement-meta">
                <span class="status-badge status-{{ $announcement->status }}">
                    <i class="fas fa-circle"></i>
                    {{ ucfirst($announcement->status) }}
                </span>
                <span class="date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $announcement->posted_at->format('M d, Y h:i A') }}
                </span>
            </div>
        </div>

        @if($announcement->image_path)
            <div class="announcement-image">
                <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}">
            </div>
        @endif

        <div class="announcement-content">
            {!! nl2br(e($announcement->content)) !!}
        </div>

        <div class="announcement-actions">
            <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Announcement
            </a>
            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">
                    <i class="fas fa-trash"></i> Delete Announcement
                </button>
            </form>
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

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

.announcement-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.announcement-header {
    margin-bottom: 1.5rem;
}

.announcement-header h2 {
    color: #1a365d;
    font-size: 1.5rem;
    margin: 0 0 1rem;
}

.announcement-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
    color: #4a5568;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-draft {
    background-color: #fef3c7;
    color: #92400e;
}

.status-published {
    background-color: #dcfce7;
    color: #166534;
}

.date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.announcement-image {
    margin: 1.5rem 0;
}

.announcement-image img {
    width: 100%;
    max-width: 600px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.announcement-content {
    font-size: 1.125rem;
    line-height: 1.6;
    color: #2d3748;
    margin: 2rem 0;
    white-space: pre-wrap;
}

.announcement-actions {
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

.btn-danger {
    background-color: #e53e3e;
    color: white;
}

.btn-danger:hover {
    background-color: #c53030;
}

.btn-secondary {
    background-color: #f7fafc;
    color: #4a5568;
}

.btn-secondary:hover {
    background-color: #e2e8f0;
}
</style>
@endsection 