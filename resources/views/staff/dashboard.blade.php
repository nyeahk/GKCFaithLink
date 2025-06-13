@extends('layouts.staff')

@section('title', 'Staff Dashboard')

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
                <i class="fas fa-plus me-1"></i> Create Event
            </a>
        </div>
        <div class="card-body">
            <div class="calendar-container">
                <div class="calendar-header">
                    <div class="calendar-title">
                        {{ isset($currentDate) ? $currentDate->format('F Y') : now()->format('F Y') }}
                    </div>
                    <div class="calendar-nav">
                        <a href="{{ route('staff.dashboard', ['timestamp' => $lastMonthTimestamp ?? now()->subMonth()->timestamp]) }}" class="calendar-nav-btn">
                            <i class="fas fa-chevron-left"></i> Prev
                        </a>
                        <a href="{{ route('staff.dashboard', ['timestamp' => $todayTimestamp ?? now()->timestamp]) }}" class="calendar-nav-btn">
                            Today
                        </a>
                        <a href="{{ route('staff.dashboard', ['timestamp' => $nextMonthTimestamp ?? now()->addMonth()->timestamp]) }}" class="calendar-nav-btn">
                            Next <i class="fas fa-chevron-right"></i>
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
                                                <i class="fas fa-calendar-event"></i>
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
@endsection

@push('styles')
<style>
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
        background: #edf2f7;
        border-radius: 0.25rem;
        color: #4a5568;
        text-decoration: none;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }

    .calendar-nav-btn:hover {
        background: #e2e8f0;
    }

    .calendar-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0.25rem;
    }

    .calendar-table th {
        padding: 0.5rem;
        text-align: center;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
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
                modalDate.textContent = data.date || 'Unknown Date';
                if (data.events && Array.isArray(data.events) && data.events.length > 0) {
                    let eventsHtml = '';
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
                eventsContainer.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <p>Error loading events. Please try again.</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
                modalDate.textContent = 'Events for ' + new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            });
    }
    // Always show today's event modal on dashboard load
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    window.showEventsForDate(todayStr);
});
</script>
@endpush
