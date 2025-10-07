@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/staff-dashboard.css') }}">
    <style>
        .btn-light:hover i {
            color: #fff !important;
        }
        .dashboard-container {
            padding: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: #3182ce;
            margin-right: 1rem;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 1rem;
            color: #4a5568;
        }

        .stat-number {
            font-size: 1.875rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0.25rem 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
            margin: 0;
        }

        .calendar-container {
            width: 100%;
            margin-bottom: 1rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .calendar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-nav-btn {
            padding: 0.5rem 0.75rem;
            background: #98D2C0;
            border-radius: 0.25rem;
            color: #000;
            text-decoration: none;
            font-size: 0.875rem;
            transition: background-color 0.2s;
            border: none;
            font-weight: 500;
        }

        .calendar-nav-btn:hover {
            background: #7ab8a3;
            color: #000;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .calendar-table th {
            padding: 0.5rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            background-color: #98D2C0 !important;
            color: #000 !important;
            border-radius: 0;
        }

        .calendar-table td {
            padding: 0.5rem;
            text-align: center;
            background: #f7fafc;
            border-radius: 0.25rem;
            height: 5rem;
            vertical-align: top;
            cursor: pointer;
            transition: background-color 0.2s;
            margin: 0.25rem;
        }

        .calendar-table td:hover {
            background: #edf2f7;
        }

        .calendar-table td.today {
            background: #ebf8ff;
            border: 2px solid #3182ce;
        }

        .calendar-table td.other-month {
            background: #f7fafc;
            color: #a0aec0;
        }

        .calendar-table td.has-events {
            background: #e6fffa;
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .event-indicator {
            font-size: 0.75rem;
            color: #2c7a7b;
            background: #b2f5ea;
            border-radius: 9999px;
            padding: 0.125rem 0.375rem;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .calendar-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .calendar-nav {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-info">
                <h3>Announcements</h3>
                <p class="stat-number">{{ $announcementsCount ?? 0 }}</p>
                <p class="stat-label">Total Announcements</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3>Events</h3>
                <p class="stat-number">{{ $eventsCount ?? 0 }}</p>
                <p class="stat-label">Upcoming Events</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Members</h3>
                <p class="stat-number">{{ $membersCount ?? 0 }}</p>
                <p class="stat-label">Total Members</p>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Event Calendar</h5>
            <a href="{{ route('staff.events.create') }}" class="btn btn-sm btn-light">
                <i class="fas fa-plus me-1" style="color: #000; transition: color 0.2s;"></i> Create Event
            </a>
        </div>
        <div class="card-body p-0">
            <div class="calendar-container">
                <div class="calendar-header">
                    <div class="calendar-title text-white">{{ $currentMonth }} {{ $currentYear }}</div>
                    <div class="calendar-nav">
                        <a href="{{ route('staff.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="calendar-nav-btn">
                            <i class="bi bi-chevron-left"></i> Prev
                        </a>
                        <a href="{{ route('staff.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="calendar-nav-btn">
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
                                        data-date="{{ $day['date'] }}"
                                        onclick="showEventsForDate('{{ $day['date'] }}')">
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

    <!-- Events Modal -->
    <div class="modal fade" id="eventsModal" tabindex="-1" aria-labelledby="eventsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="eventsModalLabel">
                        <i class="fas fa-calendar-day me-2"></i>
                        <span id="modalDate">Events</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="eventsContainer">
                        <!-- Events will be loaded here -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="createEventBtn" href="/staff/events/create" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create Event
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Past Events Archive Section -->
    @if(isset($pastEvents) && $pastEvents->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-archive me-2"></i> Past Events Archive</h5>
            <a href="{{ route('staff.events.index') }}?filter=past" class="btn btn-sm btn-light">
                <i class="fas fa-history me-1"></i> View All
            </a>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastEvents as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>
                                {{ $event->start_date->format('M d, Y') }}
                                <small class="d-block text-muted">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</small>
                            </td>
                            <td>{{ $event->location }}</td>
                            <td>
                                <span class="badge bg-{{ $event->status == 'published' ? 'success' : ($event->status == 'cancelled' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('staff.events.show', $event->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>

     // Initialize the modal
    window.eventsModal = new bootstrap.Modal(document.getElementById('eventsModal'));

    // Function to show events for a selected date
    window.showEventsForDate = function(date) {
        const modalDate = document.getElementById('modalDate');
        const eventsContainer = document.getElementById('eventsContainer');
        const createEventBtn = document.getElementById('createEventBtn');
        
        // Set loading state
        modalDate.textContent = 'Loading...';
        eventsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        // Update create event button with the selected date
        createEventBtn.href = `/staff/events/create?date=${date}`;
        
        // Show modal
        window.eventsModal.show();
        
        // Fetch events for the selected date
        fetch(`/staff/dashboard/events?date=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Debug log
                
                modalDate.textContent = data.date || 'Unknown Date';
                
                // Check if events property exists and is an array
                if (data.events && Array.isArray(data.events) && data.events.length > 0) {
                    let eventsHtml = '';
                    
                    data.events.sort((a, b) => new Date(b.start_date || b.time) - new Date(a.start_date || a.time));
                    
                    data.events.forEach(event => {
                        eventsHtml += `
                            <div class="card event-card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        ${event.title}
                                    </h5>
                                    <div class="event-time mb-2">
                                        <i class="fas fa-clock me-1"></i> ${event.time}
                                    </div>
                                    <div class="event-location mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i> ${event.location}
                                    </div>
                                    <div class="mb-3">
                                        <span class="badge bg-${event.status === 'Published' ? 'success' : 
                                                              event.status === 'Draft' ? 'secondary' : 
                                                              event.status === 'Cancelled' ? 'danger' : 'primary'}">
                                            ${event.status}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="${event.url}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="/staff/events/${event.id}/edit" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    eventsContainer.innerHTML = eventsHtml;
                } else {
                    eventsContainer.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-muted">No events scheduled for this date.</p>
                            <p>Click the "Create Event" button below to add a new event.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                eventsContainer.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <p>Error loading events. Please try again.</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
                
                // Still show the create event button even if there's an error
                modalDate.textContent = 'Events for ' + new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            });
    }
</script>
@endpush






















