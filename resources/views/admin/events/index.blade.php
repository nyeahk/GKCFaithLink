@extends('layouts.admin')

@section('title', 'Events')

@section('content')
    <div class="events-container">
        <div class="events-header">
            <div class="header-content">
                <h1>Events</h1>
                <p class="subtitle">View church events and activities</p>
            </div>
            <!-- Removed create button for admin -->
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
                                <button class="btn btn-action btn-view" title="View Event" data-event-id="{{ $event->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- Removed edit and delete buttons for admin -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No events found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="pagination">
                {{ $events->links() }}
            </div>
        @endif
    </div>

    <!-- Event Details Modal -->
    <div class="modal" id="eventDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="eventDetailsTitle">Event Details</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Modal content here -->
            </div>
        </div>
    </div>
@endsection