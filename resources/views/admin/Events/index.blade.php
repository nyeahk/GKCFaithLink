@extends('layouts.gkc')

@section('title', 'Events')

@section('content')
    <div class="events-container">
        <div class="events-header">
            <div class="header-content">
                <h1>Events</h1>
                <p class="subtitle">Manage your church events and activities</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
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
                                <button class="btn btn-action btn-view" title="View Event" data-event-id="{{ $event->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-action btn-edit" title="Edit Event">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
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
                                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Your First Event
                                    </a>
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
                    <div class="event-location-map">
                        <h4>Location</h4>
                        <div id="eventMap" style="height: 300px; width: 100%; margin-top: 1rem;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .btn-view {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }

    .btn-view:hover {
        background-color: #bee3f8;
    }

    .btn-edit {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }

    .btn-edit:hover {
        background-color: #bee3f8;
    }

    .btn-delete {
        background-color: #fff5f5;
        color: #c53030;
    }

    .btn-delete:hover {
        background-color: #fed7d7;
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

    .event-location-map {
        margin-top: 1.5rem;
        padding: 1rem;
        background-color: #f7fafc;
        border-radius: 0.5rem;
    }

    .event-location-map h4 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    #eventMap {
        height: 300px;
        width: 100%;
        margin-top: 1rem;
        border-radius: 0.5rem;
        z-index: 1;
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
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let marker;

function initMap() {
    const mapContainer = document.getElementById('eventMap');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }

    // Initialize map with default center (Manila)
    map = L.map(mapContainer).setView([14.5995, 120.9842], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

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
            <div class="event-location-map">
                <h4>Location</h4>
                <div id="eventMap" style="height: 300px; width: 100%; margin-top: 1rem;"></div>
            </div>
        `;
        eventDetailsModal.style.display = 'block';
        modalContent.style.display = 'block';

        // Initialize map if not already done
        if (!map) {
            initMap();
        }

        // Fetch event details
        fetch(`/admin/events/${eventId}`, {
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

                // Update map with event location
                if (data.location && map) {
                    // Use OpenStreetMap Nominatim for geocoding
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(data.location)}&countrycodes=ph`)
                        .then(response => response.json())
                        .then(results => {
                            if (results && results.length > 0) {
                                const location = results[0];
                                
                                // Remove existing marker if any
                                if (marker) {
                                    map.removeLayer(marker);
                                }
                                
                                // Center map on the location
                                map.setView([location.lat, location.lon], 15);
                                
                                // Add marker
                                marker = L.marker([location.lat, location.lon])
                                    .addTo(map)
                                    .bindPopup(data.title)
                                    .openPopup();
                            }
                        })
                        .catch(error => {
                            console.error('Error geocoding location:', error);
                        });
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