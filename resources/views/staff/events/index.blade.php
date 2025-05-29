@extends('layouts.gkc')

@section('title', 'Staff Events')

@section('content')
    <div class="events-container">
        <div class="events-header">
            <div class="header-content">
                <h1><i class="fas fa-calendar-alt"></i> Events Management</h1>
                <p class="subtitle">Manage church events and activities</p>
            </div>
            <a href="{{ route('staff.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Event
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="events-table">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <div class="event-title clickable" data-event-id="{{ $event->id }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $event->title }}</span>
                                </div>
                            </td>
                            <td>{{ $event->start_date->format('M d, Y h:i A') }}</td>
                            <td>{{ $event->end_date->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="event-location clickable" data-event-id="{{ $event->id }}">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $event->status }}">
                                    <i class="fas fa-circle"></i>
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('staff.events.show', $event->id) }}" class="btn btn-sm btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.events.edit', $event->id) }}" class="btn btn-sm btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this event?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-calendar-times"></i>
                                    <h3>No Events Found</h3>
                                    <p>There are no events to display. Create your first event to get started.</p>
                                    <a href="{{ route('staff.events.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Event
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="pagination">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .events-container {
        padding: 2rem;
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
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-content h1 i {
        color: #2b6cb0;
    }

    .header-content .subtitle {
        color: #4a5568;
        margin: 0.5rem 0 0;
        font-size: 1rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #2b6cb0;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .events-table {
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
        background-color: #f7fafc;
        color: #1a365d;
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

    .event-title, .event-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .event-title i, .event-location i {
        color: #2b6cb0;
    }

    .clickable {
        cursor: pointer;
        transition: color 0.2s;
    }

    .clickable:hover {
        color: #2b6cb0;
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

    .status-badge i {
        font-size: 0.5rem;
    }

    .status-published {
        background-color: #e6fffa;
        color: #2c7a7b;
    }

    .status-draft {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }

    .status-cancelled {
        background-color: #fff5f5;
        color: #c53030;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background-color: #4299e1;
        color: white;
    }

    .btn-view:hover {
        background-color: #3182ce;
    }

    .btn-edit {
        background-color: #48bb78;
        color: white;
    }

    .btn-edit:hover {
        background-color: #38a169;
    }

    .btn-delete {
        background-color: #e53e3e;
        color: white;
    }

    .btn-delete:hover {
        background-color: #c53030;
    }

    .d-inline {
        display: inline-block;
    }

    table td, table th {
        padding: 12px 16px;
        vertical-align: middle;
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

    .empty-content h3 {
        color: #2d3748;
        margin: 0;
    }

    .empty-content p {
        color: #4a5568;
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
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

    @media (max-width: 768px) {
        .events-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .events-table {
            overflow-x: auto;
        }

        table {
            min-width: 800px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event to event titles and locations
    document.querySelectorAll('.event-title, .event-location').forEach(element => {
        element.addEventListener('click', function() {
            const eventId = this.dataset.eventId;
            window.location.href = `/staff/events/${eventId}`;
        });
    });
});
</script>
@endpush
