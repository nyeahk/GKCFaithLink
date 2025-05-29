@extends('layouts.gkc')

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
    <div class="calendar-section">
        <div class="calendar-header">
            <h2>Event Calendar</h2>
            <div class="calendar-navigation">
                <a href="{{ route('staff.dashboard', ['timestamp' => $lastMonthTimestamp ?? now()->subMonth()->timestamp]) }}" class="nav-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <h3>{{ isset($currentDate) ? $currentDate->format('F Y') : now()->format('F Y') }}</h3>
                <a href="{{ route('staff.dashboard', ['timestamp' => $nextMonthTimestamp ?? now()->addMonth()->timestamp]) }}" class="nav-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <a href="{{ route('staff.dashboard', ['timestamp' => $todayTimestamp ?? now()->timestamp]) }}" class="today-btn">
                    Today
                </a>
            </div>
        </div>

        <div class="calendar-grid">
            <div class="calendar-days">
                <div class="day-name">Sun</div>
                <div class="day-name">Mon</div>
                <div class="day-name">Tue</div>
                <div class="day-name">Wed</div>
                <div class="day-name">Thu</div>
                <div class="day-name">Fri</div>
                <div class="day-name">Sat</div>
            </div>
            <div class="calendar-dates">
                @if(isset($calendar) && is_array($calendar))
                    @foreach($calendar as $index => $day)
                        <div class="calendar-cell {{ isset($day['isToday']) && $day['isToday'] ? 'today' : '' }} {{ isset($day['isCurrentMonth']) && $day['isCurrentMonth'] ? 'current-month' : 'other-month' }}"
                            data-date="{{ isset($day['date']) && $day['date'] ? $day['date']->format('Y-m-d') : '' }}"
                            onclick="{{ isset($day['date']) && $day['date'] ? 'showEvents(this)' : '' }}">
                            @if(isset($day['day']) && $day['day'])
                                <span class="date-number">{{ $day['day'] }}</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- Fallback for when calendar data is not available -->
                    @for($i = 0; $i < 35; $i++)
                        <div class="calendar-cell"></div>
                    @endfor
                @endif
            </div>
        </div>
    </div>

    <!-- Events for Selected Date -->
    <div class="events-for-date">
        <h3 id="selected-date">Select a date to view events</h3>
        <div id="events-list" class="events-list"></div>
        <div id="no-events" class="no-events">
            <p>No events scheduled for this date.</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <h3>Quick Links</h3>
        <div class="links-grid">
            <a href="{{ route('staff.events.index') }}" class="link-card">
                <i class="fas fa-calendar-alt"></i>
                <span>Manage Events</span>
            </a>
            <a href="{{ route('staff.events.create') }}" class="link-card">
                <i class="fas fa-calendar-plus"></i>
                <span>Create Event</span>
            </a>
            <a href="{{ route('staff.announcements.index') }}" class="link-card">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            <a href="{{ route('staff.profile.index') }}" class="link-card">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: #ebf8ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: #3182ce;
    }

    .stat-info h3 {
        margin: 0;
        color: #2d3748;
        font-size: 1rem;
    }

    .stat-number {
        font-size: 1.875rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0.25rem 0;
    }

    .stat-label {
        color: #718096;
        font-size: 0.875rem;
        margin: 0;
    }

    .calendar-section {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .calendar-header h2 {
        margin: 0;
        color: #2d3748;
        font-size: 1.5rem;
    }

    .calendar-navigation {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .calendar-navigation h3 {
        margin: 0;
        color: #2d3748;
        font-size: 1.25rem;
        min-width: 120px;
        text-align: center;
    }

    .nav-btn {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: #edf2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5568;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .nav-btn:hover {
        background: #e2e8f0;
    }

    .today-btn {
        padding: 0.5rem 1rem;
        background: #edf2f7;
        border-radius: 0.25rem;
        color: #4a5568;
        text-decoration: none;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }

    .today-btn:hover {
        background: #e2e8f0;
    }

    .calendar-grid {
        margin-bottom: 1rem;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .day-name {
        text-align: center;
        font-weight: 600;
        color: #4a5568;
        padding: 0.5rem;
    }

    .calendar-dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .calendar-cell {
        aspect-ratio: 1;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-cell:hover {
        background: #edf2f7;
    }

    .calendar-cell.today {
        background: #ebf8ff;
        border: 2px solid #3182ce;
    }

    .calendar-cell.other-month {
        color: #a0aec0;
    }

    .date-number {
        font-weight: 500;
        color: #2d3748;
    }

    .calendar-cell.other-month .date-number {
        color: #a0aec0;
    }

    .events-for-date {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .events-for-date h3 {
        margin: 0 0 1rem;
        color: #2d3748;
        font-size: 1.25rem;
    }

    .events-list {
        display: none;
    }

    .event-item {
        padding: 1rem;
        border-radius: 0.375rem;
        background: #f7fafc;
        margin-bottom: 0.75rem;
    }

    .event-title {
        font-weight: 600;
        color: #2d3748;
        margin: 0 0 0.5rem;
    }

    .event-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .event-time, .event-location {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-published {
        background: #e6fffa;
        color: #2c7a7b;
    }

    .status-draft {
        background: #ebf8ff;
        color: #2b6cb0;
    }

    .status-cancelled {
        background: #fff5f5;
        color: #c53030;
    }

    .no-events {
        text-align: center;
        padding: 2rem;
        color: #718096;
    }

    .quick-links {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .quick-links h3 {
        margin: 0 0 1rem;
        color: #2d3748;
        font-size: 1.25rem;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .link-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem;
        background: #f7fafc;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .link-card:hover {
        background: #edf2f7;
    }

    .link-card i {
        font-size: 2rem;
        color: #3182ce;
        margin-bottom: 0.75rem;
    }

    .link-card span {
        color: #2d3748;
        font-weight: 500;
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

        .calendar-navigation {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventsList = document.getElementById('events-list');
    const noEvents = document.getElementById('no-events');
    const selectedDateHeader = document.getElementById('selected-date');
    
    // Hide no events message initially
    noEvents.style.display = 'none';
    
    // Function to show events for a selected date
    window.showEvents = function(cell) {
        const date = cell.dataset.date;
        
        if (!date) return;
        
        // Update selected date header
        selectedDateHeader.textContent = 'Loading events...';
        
        // Fetch events for the selected date
        fetch(`/staff/dashboard/events?date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Update selected date header
                selectedDateHeader.textContent = `Events for ${data.date}`;
                
                // Clear previous events
                eventsList.innerHTML = '';
                
                // Add events to the list
                data.events.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = 'event-item';
                    eventElement.innerHTML = `
                        <h4 class="event-title">${event.title}</h4>
                        <div class="event-details">
                            <div class="event-time">
                                <i class="far fa-clock"></i>
                                <span>${event.start_time} - ${event.end_time}</span>
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${event.location}</span>
                            </div>
                            <span class="status-badge status-${event.status.toLowerCase()}">${event.status}</span>
                        </div>
                    `;
                    eventsList.appendChild(eventElement);
                });
                
                // Show/hide events list or no events message
                if (data.events.length === 0) {
                    eventsList.style.display = 'none';
                    noEvents.style.display = 'block';
                } else {
                    eventsList.style.display = 'block';
                    noEvents.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                selectedDateHeader.textContent = 'Error loading events';
                eventsList.innerHTML = `
                    <div class="text-center py-4 text-red-500">
                        <p>Error loading events.</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
                eventsList.style.display = 'block';
                noEvents.style.display = 'none';
            });
    }
    
    // Highlight today's date if available
    const today = document.querySelector('.calendar-cell.today');
    if (today) {
        today.click();
    }
});
</script>
@endpush

<!-- Add this somewhere in your dashboard view -->
<div class="debug-links" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h4>Debug Links</h4>
    <ul>
        <li><a href="{{ route('staff.events.index') }}" target="_blank">Events Index</a></li>
        <li><a href="{{ route('staff.events.create') }}" target="_blank">Create Event</a></li>
        <li><a href="{{ route('staff.announcements.index') }}" target="_blank">Announcements</a></li>
        <li><a href="{{ url('/debug-routes') }}" target="_blank">Debug Routes</a></li>
    </ul>
</div>

