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
                            <td>{{ $event->start_date->format('M d, Y g:i A') }}</td>
                            <td>{{ $event->end_date->format('M d, Y g:i A') }}</td>
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
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="text-center mb-3">
                        <img src="" alt="Event Image" class="event-image img-thumbnail mb-2" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                        <h5 class="event-title mb-1"></h5>
                        <div class="event-category badge bg-secondary mb-2"></div>
                    </div>
                    <div class="event-description small text-muted mb-3"></div>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Date & Time:</span>
                            <span class="event-date text-end"></span>
                        </li>
                        <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Location:</span>
                            <span class="event-location text-end"></span>
                        </li>
                        <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Organizer:</span>
                            <span class="event-organizer text-end"></span>
                        </li>
                        <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Attendees:</span>
                            <span class="event-attendees text-end"></span>
                        </li>
                        <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Status:</span>
                            <span class="event-status badge"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


