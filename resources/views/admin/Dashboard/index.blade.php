@extends('layouts.gkc')

@section('title', 'Admin Dashboard')

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
                    <p class="stat-number">{{ $announcementsCount }}</p>
                    <p class="stat-label">Total Announcements</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>Events</h3>
                    <p class="stat-number">{{ $eventsCount }}</p>
                    <p class="stat-label">Upcoming Events</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Members</h3>
                    <p class="stat-number">{{ $membersCount }}</p>
                    <p class="stat-label">Active Members</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-donate"></i>
                </div>
                <div class="stat-info">
                    <h3>Donations</h3>
                    <p class="stat-number">₱{{ number_format($donationsTotal, 2) }}</p>
                    <p class="stat-label">Total Donations</p>
                </div>
            </div>
        </div>

        <!-- Calendar and Recent Activities -->
        <div class="dashboard-grid">
            <!-- Calendar Section -->
            <div class="calendar-section">
                <div class="section-header">
                    <h2>Calendar</h2>
                    <div class="calendar-nav">
                        <a href="{{ route('admin.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="btn btn-sm">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <span>{{ $currentDate->format('F Y') }}</span>
                        <a href="{{ route('admin.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="btn btn-sm">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="calendar">
                    <table id="cal-table">
                        <thead>
                            <tr class="day-headings">
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
                            @foreach($calendar as $week)
                                <tr>
                                    @foreach($week as $day)
                                        <td class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isCurrentMonth'] ? '' : 'other-month' }}"
                                            data-date="{{ $day['date']->format('Y-m-d') }}"
                                            data-has-events="{{ isset($day['events']) && count($day['events']) > 0 ? 'true' : 'false' }}">
                                            <div class="day-number">{{ $day['day'] }}</div>
                                            @if(isset($day['events']) && count($day['events']) > 0)
                                                <div class="event-indicator">
                                                    @if(count($day['events']) === 1)
                                                        <a href="{{ route('admin.events.show', ['event' => $day['events'][0]->id]) }}" class="event-link">
                                                            <span class="event-dot"></span>
                                                            <span class="event-count">{{ count($day['events']) }}</span>
                                                        </a>
                                                    @else
                                                        <span class="event-link">
                                                            <span class="event-dot"></span>
                                                            <span class="event-count">{{ count($day['events']) }}</span>
                                                        </span>
                                                    @endif
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

            <!-- Recent Activities -->
            <div class="recent-activities">
                <div class="section-header">
                    <h2>Recent Activities</h2>
                </div>
                <div class="activities-list">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas {{ $activity->icon }}"></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-description">{{ $activity->description }}</p>
                                <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-activities">No recent activities</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal" id="eventModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-calendar-alt"></i>
                    Events for <span id="modalDate"></span>
                </h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="eventsList" class="events-list"></div>
                <div id="noEvents" class="no-events">
                    <div class="no-events-content">
                        <i class="fas fa-calendar-times"></i>
                        <h4>No Events Scheduled</h4>
                        <p>There are no events scheduled for this day.</p>
                        <div class="no-events-actions">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Event
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                <i class="fas fa-calendar-alt"></i> View All Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal" id="eventDetailsModal">
        <div class="modal-content event-details-content">
            <div class="modal-header">
                <h3 class="modal-title">Event Details</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="event-show-container">
                    <div class="event-header">
                        <div class="header-content">
                            <div class="header-top">
                                <h1>
                                    <i class="fas fa-calendar-alt"></i>
                                    <span id="eventTitle"></span>
                                </h1>
                                <div class="header-actions">
                                    <a href="#" class="btn btn-primary" id="editEventBtn">
                                        <i class="fas fa-edit"></i> Edit Event
                                    </a>
                                    <form action="#" method="POST" class="d-inline" id="deleteEventForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">
                                            <i class="fas fa-trash"></i> Delete Event
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="event-meta">
                                <span class="status-badge" id="eventStatus">
                                    <i class="fas fa-circle"></i>
                                    <span id="statusText"></span>
                                </span>
                                <div class="meta-items">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span id="eventDate"></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span id="eventTime"></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span id="eventLocation"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-content">
                        <div class="event-image" id="eventImageContainer">
                            <img id="eventImage" src="" alt="Event Image">
                        </div>

                        <div class="event-details">
                            <div class="detail-section">
                                <h3><i class="fas fa-info-circle"></i> Description</h3>
                                <div class="description-content">
                                    <p id="eventDescription"></p>
                                </div>
                            </div>

                            <div class="detail-section">
                                <h3><i class="fas fa-map-marker-alt"></i> Location Details</h3>
                                <div class="location-content">
                                    <p id="eventLocationDetails"></p>
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
            </div>
        </div>
    </div>

 
@endsection

@push('styles')
<style>
    .dashboard-container {
        padding: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--primary-dark);
    }

    .stat-info h3 {
        margin: 0;
        font-size: 1rem;
        color: var(--text-secondary);
    }

    .stat-number {
        margin: 0.25rem 0;
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .stat-label {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .calendar-section, .recent-activities {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .calendar table {
        width: 100%;
        border-collapse: collapse;
    }

    .calendar th, .calendar td {
        padding: 0.75rem;
        text-align: center;
        border: 1px solid var(--border);
    }

    .calendar th {
        background: var(--background-light);
        font-weight: 600;
    }

    .calendar td {
        height: 60px;
        vertical-align: top;
    }

    .day-number {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .today {
        background: var(--primary-light);
    }

    .other-month {
        color: var(--text-secondary);
    }

    .event-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .event-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--primary);
    }

    .event-count {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .activities-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 4px;
        background: var(--background-light);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-icon i {
        color: var(--primary-dark);
    }

    .activity-details {
        flex: 1;
    }

    .activity-description {
        margin: 0;
        color: var(--text-primary);
    }

    .activity-time {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .no-activities {
        text-align: center;
        color: var(--text-secondary);
        padding: 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .calendar-day {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: #ebf8ff;
    }

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
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 1rem auto;
        overflow: hidden;
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        background: var(--background-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-title i {
        color: var(--primary);
        font-size: 1em;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .modal-close:hover {
        color: var(--text-primary);
        background: var(--background-light);
    }

    .modal-body {
        padding: 1rem;
    }

    .events-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .event-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 6px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .event-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .event-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .event-icon i {
        font-size: 1rem;
        color: var(--primary);
    }

    .event-details {
        flex: 1;
    }

    .event-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        text-decoration: none;
        transition: color 0.2s;
        display: block;
    }

    .event-title:hover {
        color: var(--primary);
    }

    .event-meta {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        color: var(--text-secondary);
        font-size: 0.8125rem;
    }

    .event-time, .event-location {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .event-time i, .event-location i {
        width: 14px;
        color: var(--primary);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.6875rem;
        font-weight: 500;
        margin-top: 0.375rem;
    }

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-badge.cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .no-events {
        text-align: center;
        padding: 2rem 1rem;
    }

    .no-events-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .no-events i {
        font-size: 2.5rem;
        color: var(--text-secondary);
        opacity: 0.5;
    }

    .no-events h4 {
        color: var(--text-primary);
        font-size: 1.1rem;
        margin: 0;
    }

    .no-events p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .no-events-actions {
        display: flex;
        gap: 0.75rem;
    }

    @media (max-width: 768px) {
        .modal-content {
            margin: 0.5rem;
        }

        .event-item {
            flex-direction: column;
        }

        .no-events-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    .event-show-container {
        padding: 0;
        max-width: 100%;
        margin: 0;
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

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-badge.cancelled {
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

    .event-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }

    .event-link:hover {
        color: var(--primary);
    }

    .event-link:hover .event-dot {
        background: var(--primary);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('eventModal');
    const modalDate = document.getElementById('modalDate');
    const eventsList = document.getElementById('eventsList');
    const noEvents = document.getElementById('noEvents');
    const closeBtns = document.querySelectorAll('.modal-close');

    // Add click event to calendar days
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.dataset.date;
            const hasEvents = this.dataset.hasEvents === 'true';
            
            modalDate.textContent = new Date(date).toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            if (hasEvents) {
                // Show loading state
                eventsList.innerHTML = '<div class="text-center py-4">Loading events...</div>';
                eventsList.style.display = 'block';
                noEvents.style.display = 'none';
                
                // Fetch events for the selected date
                fetch(`/admin/dashboard/events?date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        eventsList.innerHTML = '';
                        data.events.forEach(event => {
                            const eventElement = document.createElement('div');
                            eventElement.className = 'event-item';
                            eventElement.innerHTML = `
                                <div class="event-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="event-details">
                                    <a href="/admin/events/${event.id}" class="event-title">${event.title}</a>
                                    <div class="event-meta">
                                        <div class="event-time">
                                            <i class="fas fa-clock"></i>
                                            <span>${event.start_time} - ${event.end_time}</span>
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>${event.location}</span>
                                        </div>
                                    </div>
                                    <span class="status-badge ${event.status.toLowerCase()}">${event.status}</span>
                                </div>
                            `;
                            eventsList.appendChild(eventElement);
                        });
                    })
                    .catch(error => {
                        eventsList.innerHTML = `
                            <div class="text-center py-4 text-red-500">
                                <p>Error loading events.</p>
                                <p class="text-sm">${error.message}</p>
                            </div>
                        `;
                    });
            } else {
                eventsList.style.display = 'none';
                noEvents.style.display = 'block';
            }
            
            modal.style.display = 'block';
        });
    });

    // Close modals
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

function showEventDetails(eventId) {
    fetch(`/admin/dashboard/events/${eventId}`)
        .then(response => response.json())
        .then(data => {
            eventsList.innerHTML = '';
            data.events.forEach(event => {
                const eventElement = document.createElement('div');
                eventElement.className = 'event-item';
                eventElement.innerHTML = `
                    <div class="event-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="event-details">
                        <a href="/admin/events/${event.id}" class="event-title">${event.title}</a>
                        <div class="event-meta">
                            <div class="event-time">
                                <i class="fas fa-clock"></i>
                                <span>${event.start_time} - ${event.end_time}</span>
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${event.location}</span>
                            </div>
                        </div>
                        <span class="status-badge ${event.status.toLowerCase()}">${event.status}</span>
                    </div>
                `;
                eventsList.appendChild(eventElement);
            });
            
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
            eventsList.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <p>Error loading events.</p>
                    <p class="text-sm">${error.message}</p>
                </div>
            `;
        });
}
</script>
@endpush