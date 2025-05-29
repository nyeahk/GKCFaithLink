@extends('layouts.gkc')

@section('title', $announcement->title)

@section('content')
    <div class="announcements-container">
        <div class="announcements-header">
            <h1><i class="fas fa-bullhorn"></i> Announcement Details</h1>
            <div class="header-actions">
                <a href="{{ route('staff.announcements.edit', $announcement->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('staff.announcements.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Announcements
                </a>
            </div>
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
                <a href="{{ route('staff.announcements.edit', $announcement->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Announcement
                </a>
                <form action="{{ route('staff.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">
                        <i class="fas fa-trash"></i> Delete Announcement
                    </button>
                </form>
            </div>
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn-edit {
        background: #38b2ac;
        color: white;
    }

    .btn-edit:hover {
        background: #319795;
    }

    .btn-back {
        background: #edf2f7;
        color: #4a5568;
    }

    .btn-back:hover {
        background: #e2e8f0;
    }

    .announcement-card {
        background: white;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .announcement-header {
        margin-bottom: 1.5rem;
    }

    .announcement-header h2 {
        margin: 0 0 0.5rem;
        font-size: 1.5rem;
        color: #2d3748;
    }

    .announcement-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-published {
        background: #e6fffa;
        color: #2c7a7b;
    }

    .status-draft {
        background: #ebf8ff;
        color: #2b6cb0;
    }

    .status-pending {
        background: #fefcbf;
        color: #975a16;
    }

    .date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: #718096;
        font-size: 0.875rem;
    }

    .announcement-image {
        margin-bottom: 1.5rem;
    }

    .announcement-image img {
        max-width: 100%;
        border-radius: 0.375rem;
    }

    .announcement-content {
        margin-bottom: 2rem;
        line-height: 1.6;
        color: #4a5568;
    }

    .announcement-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        border-top: 1px solid #e2e8f0;
        padding-top: 1.5rem;
    }

    .btn-primary {
        background: #4299e1;
        color: white;
    }

    .btn-primary:hover {
        background: #3182ce;
    }

    .btn-danger {
        background: #e53e3e;
        color: white;
    }

    .btn-danger:hover {
        background: #c53030;
    }

    @media (max-width: 768px) {
        .announcements-container {
            padding: 1rem;
        }

        .announcements-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
        }

        .announcement-card {
            padding: 1.5rem;
        }

        .announcement-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush