@extends('layouts.gkc')

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
            <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
@endsection

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