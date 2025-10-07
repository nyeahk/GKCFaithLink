@extends('layouts.member')

@section('title', 'Calendar Dashboard')
@section('page-title', 'Event Calendar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i> Event Calendar</h5>
                </div>
                <div class="card-body">
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <div class="calendar-title text-white">{{ $currentMonth }} {{ $currentYear }}</div>
                            <div class="calendar-nav">
                                <a href="{{ route('member.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="calendar-nav-btn">
                                    <i class="bi bi-chevron-left"></i> Prev
                                </a>
                                <a href="{{ route('member.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="calendar-nav-btn">
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
                                                        <i class="bi bi-calendar-event"></i>
                                                        <span class="event-count">{{ count($day['events']) }}</span>
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
        
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($upcomingEvents as $event)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-calendar me-1"></i> {{ $event->start_date->format('F d, Y') }}
                                                <br>
                                                <i class="bi bi-clock me-1"></i> {{ $event->start_date->format('h:i A') }}
                                            </p>
                                        </div>
                                        <a href="{{ route('member.events.show', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('member.events') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-calendar3 me-1"></i> View All Events
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No upcoming events scheduled.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-megaphone me-2"></i> Recent Announcements</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentAnnouncements as $announcement)
                                <li class="list-group-item">
                                    <h6 class="mb-1">{{ $announcement->title }}</h6>
                                    <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history me-1"></i> {{ $announcement->created_at->diffForHumans() }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('member.announcements') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-megaphone me-1"></i> View All Announcements
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No recent announcements.</p>
                        </div>
                    @endif
                </div>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        
        // Fetch events for the selected date - use member route
        fetch(`/member/events/date/${date}`)
            .then(response => response.json())
            .then(data => {
                modalDate.textContent = data.date;

                if (data.hasEvents) {
                    // Sort events by start_datetime ascending (earliest first)
                    data.events.sort((a, b) => new Date(a.start_datetime) - new Date(b.start_datetime));

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
                                    <p class="card-text">${event.description}</p>
                                    <a href="/member/events/${event.id}" class="btn btn-sm btn-primary">
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

    console.log("Before Sorting:", data.events);
data.events.sort((a, b) => new Date(a.start_datetime) - new Date(b.start_datetime));
console.log("After Sorting:", data.events);
</script>
@endpush

@push('styles')
<style>
    /* Enhanced dashboard styles using app color scheme */
    .calendar-container {
        background-color: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 8px var(--shadow);
    }
    
    .calendar-header {
        background-color: var(--primary);
        color: var(--white);
    }
    
    .calendar-title {
        font-weight: 600;
    }
    
    .calendar-nav-btn {
        background-color: var(--primary-light);
        color: var(--white);
        border: none;
        transition: var(--hover-transition);
    }
    
    .calendar-nav-btn:hover {
        background-color: var(--primary-dark);
        transform: var(--hover-scale);
    }
    
    .calendar-table th {
        background-color: var(--primary-light);
        color: var(--primary-dark);
    }
    
    .today {
        background-color: var(--background-light);
    }
    
    .event-indicator {
        background-color: var(--primary-light);
        color: var(--primary-dark);
    }
    
    .card {
        transition: var(--hover-transition);
    }
    
    .card:hover {
        transform: var(--hover-scale);
        box-shadow: var(--hover-shadow);
    }
    
    /* Change card headers from blue to #4F959D */
    .card-header {
        background-color: #4F959D !important;
        color: var(--white);
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
</style>
@endpush




