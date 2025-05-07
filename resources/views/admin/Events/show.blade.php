
@extends('layouts.admin')

@section('title', 'View Event')

@section('content')
    <div class="event-show-container">
        <div class="event-header">
            <div class="header-content">
                <div class="header-top">
                    <h1>
                        <i class="fas fa-calendar-alt"></i>
                        {{ $event->title }}
                    </h1>
                    <div class="header-actions">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">
                                <i class="fas fa-trash"></i> Delete Event
                            </button>
                        </form>
                    </div>
                </div>
                <div class="event-meta">
                    <span class="status-badge status-{{ $event->status }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($event->status) }}
                    </span>
                    <div class="meta-items">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $event->start_date->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-content">
            @if($event->image_path)
                <div class="event-image">
                    <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}">
                </div>
            @endif

            <div class="event-details">
                <div class="detail-section">
                    <h3><i class="fas fa-info-circle"></i> Description</h3>
                    <div class="description-content">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Location Details</h3>
                    <div class="location-content">
                        <p>{{ $event->location }}</p>
                        <div id="eventMap" class="event-map"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-footer">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .event-show-container {
        padding: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .event-header {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .header-content {
        padding: 2rem;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .event-header h1 {
        color: var(--text-primary);
        font-size: 2rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .event-header h1 i {
        color: var(--primary);
        font-size: 1.8rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .event-meta {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .meta-items {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
    }

    .meta-item i {
        color: var(--primary);
        width: 16px;
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

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .event-content {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .event-image {
        width: 100%;
        max-height: 400px;
        overflow: hidden;
    }

    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-details {
        padding: 2rem;
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .detail-section h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-section h3 i {
        color: var(--primary);
    }

    .description-content {
        background: var(--background-light);
        padding: 1.5rem;
        border-radius: 8px;
        color: var(--text-secondary);
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .location-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .location-content p {
        color: var(--text-secondary);
        margin: 0;
    }

    .event-map {
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
    }

    .event-footer {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-secondary {
        background-color: var(--background-light);
        color: var(--text-primary);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background-color: var(--border);
    }

    @media (max-width: 768px) {
        .event-show-container {
            padding: 1rem;
        }

        .header-top {
            flex-direction: column;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .meta-items {
            flex-direction: column;
            gap: 1rem;
        }

        .event-details {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map if element exists
    const mapElement = document.getElementById('eventMap');
    if (mapElement) {
        const map = L.map('eventMap').setView([0, 0], 2);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Geocode the location
        const location = "{{ $event->location }}";
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    map.setView([lat, lon], 15);
                    L.marker([lat, lon]).addTo(map)
                        .bindPopup(location)
                        .openPopup();
                }
            })
            .catch(error => {
                console.error('Error geocoding location:', error);
            });
    }
});
</script>
@endpush 
