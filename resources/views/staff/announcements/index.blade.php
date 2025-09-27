@extends('layouts.staff')

@section('title', 'Announcements')

@section('content')
    <div class="announcements-container">
        <div class="announcements-header">
            <div class="header-content">
                <h1>Announcements</h1>
                <p class="subtitle">Manage and publish announcements for your community</p>
            </div>
            <a href="{{ route('staff.announcements.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Announcement
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter tabs -->
        <div class="announcements-filter-tabs mb-4">
            <a href="{{ route('staff.announcements.index') }}?filter=all"
               class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i> All Announcements
            </a>
            <a href="{{ route('staff.announcements.index') }}?filter=current"
               class="filter-tab {{ $filter === 'current' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> This Week
            </a>
            <a href="{{ route('staff.announcements.index') }}?filter=recent"
               class="filter-tab {{ $filter === 'recent' ? 'active' : '' }}">
                <i class="fas fa-calendar-day"></i> This Month
            </a>
            <a href="{{ route('staff.announcements.index') }}?filter=older"
               class="filter-tab {{ $filter === 'older' ? 'active' : '' }}">
                <i class="fas fa-archive"></i> Older
            </a>
        </div>

        <div class="announcements-table">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Posted At</th>
                        <th>Time Status</th>
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
                            <td>
                                <span class="badge {{ $announcement->time_class ?? 'badge-secondary' }}">
                                    {{ $announcement->time_label ?? 'Unknown' }}
                                </span>
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

    .header-content .subtitle {
        color: #4a5568;
        margin: 0.5rem 0 0;
        font-size: 1rem;
    }

    .announcements-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #367588 !important;
        color: #fff !important;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #4a5568;
    }

    .announcement-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .announcement-thumbnail {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        object-fit: cover;
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

    .date-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4a5568;
    }

    .date-info .time {
        color: #718096;
        font-size: 0.875rem;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 4px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .btn-view {
        background-color: var(--primary-dark);
    }

    .btn-view:hover {
        background-color: var(--accent-teal);
    }

    .btn-edit {
        background-color: #f59e0b;
    }

    .btn-edit:hover {
        background-color: #d97706;
    }

    .btn-delete {
        background-color: #ef4444;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
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
        color: #4a5568;
        font-size: 1.125rem;
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

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        color: #2b6cb0;
        background-color: white;
        border: 1px solid #e2e8f0;
    }

    .pagination .page-item.active .page-link {
        background-color: #2b6cb0;
        color: white;
        border-color: #2b6cb0;
    }

    /* Filter Tabs */
    .announcements-filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.5rem;
    }

    .filter-tab {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #6b7280;
        border-radius: 0.375rem 0.375rem 0 0;
        transition: all 0.2s;
        font-weight: 500;
        border-bottom: 2px solid transparent;
    }

    .filter-tab:hover {
        color: #374151;
        background-color: #f9fafb;
        text-decoration: none;
    }

    .filter-tab.active {
        color: #1f2937;
        background-color: #f3f4f6;
        border-bottom-color: #3b82f6;
    }

    .filter-tab i {
        margin-right: 0.5rem;
    }

    /* Time Status Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-primary {
        color: #ffffff;
        background-color: #007bff;
    }

    .badge-success {
        color: #ffffff;
        background-color: #28a745;
    }

    .badge-info {
        color: #ffffff;
        background-color: #17a2b8;
    }

    .badge-secondary {
        color: #ffffff;
        background-color: #6c757d;
    }

    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }
</style>
@endpush 
