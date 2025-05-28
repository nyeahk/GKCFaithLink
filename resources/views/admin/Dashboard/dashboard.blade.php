@extends('layouts.admin')

@section('title', 'Calendar Dashboard')
@section('page-title', 'Event Calendar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="calendar-container">
                <div class="calendar-header">
    <div class="calendar-title">
        {{ $currentDate->format('F Y') }}
    </div>
    <div class="calendar-nav">
        <a href="{{ route('admin.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="calendar-nav-btn">
            <i class="bi bi-chevron-left"></i> Prev
        </a>
        <a href="{{ route('admin.dashboard', ['timestamp' => $todayTimestamp]) }}" class="calendar-nav-btn">
            Today
        </a>
        <a href="{{ route('admin.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="calendar-nav-btn">
            Next <i class="bi bi-chevron-right"></i>
        </a>
    </div>
</div>
                
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calendar as $week)
                            <tr>
                                @foreach ($week as $day)
                                    <td class="{{ !$day['isCurrentMonth'] ? 'other-month' : '' }} 
                                             {{ $day['isToday'] ? 'today' : '' }}
                                             {{ isset($day['events']) && count($day['events']) > 0 ? 'has-events' : '' }}" 
                                        onclick="showEventsForDate('{{ $day['date']->format('Y-m-d') }}')">
                                        <div class="day-number">{{ $day['day'] }}</div>
                                        @if(isset($day['events']) && count($day['events']) > 0)
                                            <div class="event-indicator">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ count($day['events']) }} event(s)
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Events Modal -->
<div class="modal fade" id="eventsModal" tabindex="-1" aria-labelledby="eventsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventsModalLabel">
                    <i class="bi bi-calendar-event me-2"></i>
                    Events for <span id="modalDate"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventsContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modal
        window.eventsModal = new bootstrap.Modal(document.getElementById('eventsModal'));
    });
    
    function showEventsForDate(date) {
        const modalDate = document.getElementById('modalDate');
        const eventsContainer = document.getElementById('eventsContainer');
        
        // Set loading state
        modalDate.textContent = 'Loading...';
        eventsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        // Show modal
        window.eventsModal.show();
        
        // Fetch events for the selected date
        fetch(`/admin/events/date/${date}`)
            .then(response => response.json())
            .then(data => {
                modalDate.textContent = data.date;
                
                if (data.hasEvents) {
                    let eventsHtml = '';
                    
                    data.events.forEach(event => {
                        eventsHtml += `
                            <div class="card event-card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        ${event.title}
                                    </h5>
                                    <div class="event-time mb-2">
                                        <i class="bi bi-clock me-1"></i> ${event.start_time} - ${event.end_time}
                                    </div>
                                    <div class="event-location mb-2">
                                        <i class="bi bi-geo-alt me-1"></i> ${event.location}
                                    </div>
                                    <div class="mb-3">
                                        <span class="badge ${event.status_class}">${event.status}</span>
                                    </div>
                                    <p class="card-text">${event.description}</p>
                                    <a href="/admin/events/${event.id}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    
                    eventsContainer.innerHTML = eventsHtml;
                } else {
                    eventsContainer.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-muted">No events scheduled for this date.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                eventsContainer.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <p>Error loading events. Please try again.</p>
                    </div>
                `;
            });
    }
</script>
@endpush