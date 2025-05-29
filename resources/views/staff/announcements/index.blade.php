@extends('layouts.gkc')

@section('title', 'Staff Announcements')

@section('content')
    <div class="announcements-container">
        <div class="announcements-header">
            <div class="header-content">
                <h1><i class="fas fa-bullhorn"></i> Announcements</h1>
                <p class="subtitle">Manage and publish announcements for your community</p>
            </div>
            <div>
                <a href="{{ route('staff.announcements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Announcement
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="announcements-table">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Posted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td>
                                <div class="announcement-title">
                                    @if($announcement->image)
                                        <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->title }}" class="announcement-thumbnail">
                                    @endif
                                    <span>{{ $announcement->title }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $announcement->status }}">
                                    <i class="fas fa-circle"></i>
                                    {{ ucfirst($announcement->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $announcement->posted_at->format('M d, Y') }}
                                    <span class="time">{{ $announcement->posted_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="{{ route('staff.announcements.show', $announcement->id) }}" class="btn btn-action btn-view" title="View Announcement">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.announcements.edit', $announcement->id) }}" class="btn btn-action btn-edit" title="Edit Announcement">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" title="Delete Announcement" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-bullhorn"></i>
                                    <p>No announcements found</p>
                                    <a href="{{ route('staff.announcements.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Your First Announcement
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
            <div class="pagination">
                {{ $announcements->links() }}
            </div>
        @endif
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

    .header-content h1 {
        margin: 0;
        font-size: 1.75rem;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .subtitle {
        margin: 0.5rem 0 0;
        color: #718096;
    }

    .announcements-table {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f7fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .announcement-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .announcement-thumbnail {
        width: 3rem;
        height: 3rem;
        object-fit: cover;
        border-radius: 0.25rem;
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

    .date-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        color: #4a5568;
    }

    .time {
        color: #718096;
        font-size: 0.875rem;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: #4299e1;
    }

    .btn-edit {
        background: #38b2ac;
    }

    .btn-delete {
        background: #e53e3e;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content i {
        font-size: 3rem;
        color: #cbd5e0;
    }

    .empty-content p {
        color: #718096;
        margin: 0;
    }

    .pagination {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>
@endpush