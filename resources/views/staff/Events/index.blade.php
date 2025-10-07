@extends('layouts.staff')

@section('title', 'Events')

@section('content')
    <div class="events-container">
        <div class="events-header">
            <div class="header-content">
                <h1>Events</h1>
                <p class="subtitle">Manage your church events and activities</p>
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

        <form method="GET" action="{{ route('staff.events.index') }}" class="mb-4">
            <div class="input-group shadow-sm rounded-pill" style="overflow: hidden;">
                <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                    <i class="bi bi-search text-secondary"></i>
                </span>
                <input type="text" name="search" class="form-control border-0" placeholder="Search events..." value="{{ request('search') }}" style="background: #f8fafc;">
                <button class="btn btn-primary rounded-pill px-4" type="submit" style="margin-left: -10px;">
                    Search
                </button>
            </div>
        </form>

        <!-- Filter tabs -->
        <div class="events-filter-tabs mb-4">
            <a href="{{ route('staff.events.index') }}?filter=all"
               class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> All Events
            </a>
            <a href="{{ route('staff.events.index') }}?filter=upcoming"
               class="filter-tab {{ $filter === 'upcoming' ? 'active' : '' }}">
                <i class="fas fa-calendar-day"></i> Upcoming
            </a>
            <a href="{{ route('staff.events.index') }}?filter=current"
               class="filter-tab {{ $filter === 'current' ? 'active' : '' }}">
                <i class="fas fa-play-circle"></i> Happening Now
            </a>
            <a href="{{ route('staff.events.index') }}?filter=past"
               class="filter-tab {{ $filter === 'past' ? 'active' : '' }}">
                <i class="fas fa-history"></i> Past Events
            </a>
        </div>

        <div class="events-table">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
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
                                <button class="btn btn-action btn-view"  title="View Event" data-event-id="{{ $event->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('staff.events.attendees', $event->id) }}" class="btn btn-action btn-attendees" title="View Attendees">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{ route('staff.events.edit', $event->id) }}" class="btn btn-action btn-edit" title="Edit Event">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" title="Delete Event" onclick="return confirm('Are you sure you want to delete this event?')">
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
                                    <p>No events found</p>
                                    <a href="{{ route('staff.events.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Your First Event
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($events) && method_exists($events, 'hasPages') && $events->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
            </div>
            <div>
                {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
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
                <div class="event-details-content">
                    <div class="event-info">
                        <div class="info-row">
                            <i class="fas fa-calendar"></i>
                            <span id="eventDetailsDate">-</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-clock"></i>
                            <span id="eventDetailsTime">-</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="eventDetailsLocation">-</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-info-circle"></i>
                            <span id="eventDetailsStatus" class="status-badge">-</span>
                        </div>
                    </div>
                    <div class="event-description">
                        <h4>Description</h4>
                        <p id="eventDetailsDescription">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .input-group .form-control:focus {
    box-shadow: none;
    background: #f1f5f9;
}
.input-group .btn-primary {
    background: #20798c;
    border: none;
    font-weight: 500;
    transition: background 0.2s;
}
.input-group .btn-primary:hover {
    background: #155d6b;
}

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
        background-color: #367588 !important;
        color: #fff !important;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    th.text-center {
        text-align: center;
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

    .status-draft {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-published {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Align actions with status */
    td:last-child {
        vertical-align: middle;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 2.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        border: none;
        background: transparent;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-action i {
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .btn-view {
        color: #2b6cb0;
    }

    .btn-view:hover {
        background-color: transparent;
    }

    .btn-view:hover i {
        color: #1e40af;
        transform: scale(1.1);
    }

    .btn-attendees {
        color: #059669;
    }

    .btn-attendees:hover {
        background-color: transparent;
    }

    .btn-attendees:hover i {
        color: #047857;
        transform: scale(1.1);
    }

    .btn-edit {
        color: #2b6cb0;
    }

    .btn-edit:hover {
        background-color: transparent;
    }

    .btn-edit:hover i {
        color: #1e40af;
        transform: scale(1.1);
    }

    .btn-delete {
        color: #c53030;
    }

    .btn-delete:hover {
        background-color: transparent;
    }

    .btn-delete:hover i {
        color: #991b1b;
        transform: scale(1.1);
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

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        position: relative;
        background-color: white;
        margin: 10% auto;
        padding: 0;
        width: 90%;
        max-width: 500px;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        color: #1a365d;
        font-size: 1.25rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #4a5568;
        cursor: pointer;
        padding: 0.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .event-details-content {
        padding: 1rem;
    }

    .event-info {
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .info-row i {
        width: 24px;
        color: #4a5568;
        margin-right: 0.75rem;
    }

    .event-description {
        background-color: #f7fafc;
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .event-description h4 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .event-description p {
        color: #4a5568;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .event-image {
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .event-image img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Filter tabs styling */
    .events-filter-tabs {
        display: flex;
        border-bottom: 1px solid #dee2e6;
        margin-top: 1rem;
    }

    .filter-tab {
        padding: 0.75rem 1.5rem;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .filter-tab:hover {
        color: #ff6b6b;
        border-bottom-color: #ffcece;
    }

    .filter-tab.active {
        color: #ff6b6b;
        border-bottom-color: #ff6b6b;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventDetailsModal = document.getElementById('eventDetailsModal');
    const modalContent = document.querySelector('.modal-content');
    const closeBtns = document.querySelectorAll('.modal-close');

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === eventDetailsModal) {
            closeModal(eventDetailsModal);
        }
    });

    // Close modal when clicking close buttons
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });

    // Add click event to event titles, locations, and view buttons
    document.querySelectorAll('.event-title, .event-location, .btn-view').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const eventId = this.dataset.eventId;
            showEventDetails(eventId);
        });
    });

    function showEventDetails(eventId) {
        // Show loading state
        const eventDetailsContent = document.querySelector('.event-details-content');
        eventDetailsContent.innerHTML = `
            <div class="event-info">
                <div class="info-row">
                    <i class="fas fa-calendar"></i>
                    <span id="eventDetailsDate">-</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-clock"></i>
                    <span id="eventDetailsTime">-</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="eventDetailsLocation">-</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-info-circle"></i>
                    <span id="eventDetailsStatus" class="status-badge">-</span>
                </div>
            </div>
            <div class="event-description">
                <h4>Description</h4>
                <p id="eventDetailsDescription">-</p>
            </div>
        `;
        eventDetailsModal.style.display = 'block';
        modalContent.style.display = 'block';

        // Fetch event details
        fetch(`/staff/events/${eventId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Event data:', data); // Debug log
                
                // Update modal content with the JSON data
                const titleElement = document.getElementById('eventDetailsTitle');
                const dateElement = document.getElementById('eventDetailsDate');
                const timeElement = document.getElementById('eventDetailsTime');
                const locationElement = document.getElementById('eventDetailsLocation');
                const statusElement = document.getElementById('eventDetailsStatus');
                const descriptionElement = document.getElementById('eventDetailsDescription');

                if (titleElement) titleElement.textContent = data.title;
                if (dateElement) dateElement.textContent = `${data.start_date} to ${data.end_date}`;
                if (timeElement) timeElement.textContent = `${data.start_time} - ${data.end_time}`;
                if (locationElement) locationElement.textContent = data.location;
                if (statusElement) {
                    statusElement.textContent = data.status;
                    statusElement.className = `status-badge status-${data.status}`;
                }
                if (descriptionElement) descriptionElement.textContent = data.description;

                // Add image if it exists
                if (data.image_path) {
                    console.log('Image path:', data.image_path); // Debug log
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'event-image';
                    const image = document.createElement('img');
                    image.src = `/storage/${data.image_path}`;
                    image.alt = data.title;
                    image.onerror = function() {
                        console.error('Failed to load image:', this.src);
                        this.parentNode.remove(); // Remove the image container if image fails to load
                    };
                    imageContainer.appendChild(image);
                    eventDetailsContent.insertBefore(imageContainer, eventDetailsContent.firstChild);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                eventDetailsContent.innerHTML = `
                    <div class="text-center py-4 text-red-500">
                        <p>Error loading event details.</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
            });
    }

    function closeModal(modal) {
        if (modal) {
            modal.style.display = 'none';
            modalContent.style.display = 'none';
        }
    }
});
</script>
@endpush 