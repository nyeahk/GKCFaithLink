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
                                <button class="action-btn view-btn" onclick="viewEventDetails({{ $event->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.events.attendees', $event->id) }}" class="action-btn attendees-btn" title="View Attendees">
                                    <i class="fas fa-users"></i>
                                </a>
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
    <div id="eventDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalEventTitle"></h2>
                <span class="close" onclick="closeEventModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="eventDetailsContent">
                    <!-- Event details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .action-btn {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.2s;
        color: #6c757d;
    }
    
    .action-btn:hover {
        background-color: #e9ecef;
        color: #495057;
    }
    
    .view-btn:hover {
        color: #007bff;
    }

    .attendees-btn:hover {
        color: #28a745;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h2 {
        margin: 0;
        color: #343a40;
        font-size: 1.5rem;
    }
    
    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }
    
    .close:hover {
        color: #000;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .event-detail-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .event-detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        min-width: 120px;
        margin-right: 1rem;
    }
    
    .detail-value {
        color: #6c757d;
        flex: 1;
    }
    
    .event-description {
        white-space: pre-line;
        line-height: 1.6;
    }
</style>
@endpush

@push('scripts')
<script>
function viewEventDetails(eventId) {
    // Show loading state
    document.getElementById('eventDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    document.getElementById('eventDetailsModal').style.display = 'block';
    
    // Fetch event details
    fetch(`/admin/events/${eventId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const event = data.event;
                document.getElementById('modalEventTitle').textContent = event.title;
                
                const content = `
                    <div class="event-detail-item">
                        <div class="detail-label">Title:</div>
                        <div class="detail-value">${event.title}</div>
                    </div>
                    <div class="event-detail-item">
                        <div class="detail-label">Description:</div>
                        <div class="detail-value event-description">${event.description || 'No description provided'}</div>
                    </div>
                    <div class="event-detail-item">
                        <div class="detail-label">Start Date:</div>
                        <div class="detail-value">${new Date(event.start_date).toLocaleString()}</div>
                    </div>
                    <div class="event-detail-item">
                        <div class="detail-label">End Date:</div>
                        <div class="detail-value">${new Date(event.end_date).toLocaleString()}</div>
                    </div>
                    <div class="event-detail-item">
                        <div class="detail-label">Location:</div>
                        <div class="detail-value">${event.location}</div>
                    </div>
                    <div class="event-detail-item">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <span class="status-badge status-${event.status}">
                                <i class="fas fa-circle"></i>
                                ${event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                            </span>
                        </div>
                    </div>
                    ${event.notes ? `
                    <div class="event-detail-item">
                        <div class="detail-label">Notes:</div>
                        <div class="detail-value">${event.notes}</div>
                    </div>
                    ` : ''}
                `;
                
                document.getElementById('eventDetailsContent').innerHTML = content;
            } else {
                document.getElementById('eventDetailsContent').innerHTML = '<div class="text-center text-danger">Error loading event details</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('eventDetailsContent').innerHTML = '<div class="text-center text-danger">Error loading event details</div>';
        });
}

function closeEventModal() {
    document.getElementById('eventDetailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('eventDetailsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
@endpush


