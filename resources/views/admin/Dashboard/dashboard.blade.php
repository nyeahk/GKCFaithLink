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
                                        data-date="{{ $day['date']->format('Y-m-d') }}"
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

@if(isset($archivedEvents) && $archivedEvents->count() > 0)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="bi bi-archive me-2"></i> Past Events Archive</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivedEvents as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>
                            {{ $event->start_date->format('M d, Y') }}
                            <small class="d-block text-muted">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</small>
                        </td>
                        <td>{{ $event->location }}</td>
                        <td><span class="badge bg-{{ $event->status == 'cancelled' ? 'danger' : 'secondary' }}">{{ ucfirst($event->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Weekly Donation Chart -->
@if(isset($weeklyDonations) && $weeklyDonations->count() > 0)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i> Weekly Donation Trends</h5>
    </div>
    <div class="card-body">
        <canvas id="weeklyDonationChart" height="200"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('weeklyDonationChart').getContext('2d');
        const weeklyDonationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($donationDays),
                datasets: [{
                    label: 'Daily Donations (₱)',
                    data: @json($donationAmounts),
                    backgroundColor: 'rgba(79, 149, 157, 0.7)',
                    borderColor: 'rgba(79, 149, 157, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Donations: ₱' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif
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





