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
    </div>

    <!-- Management Tabs -->
    <div class="management-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="calendar">Calendar</button>
            <button class="tab-btn" data-tab="announcements">Announcements</button>
            <button class="tab-btn" data-tab="events">Events</button>
            <button class="tab-btn" data-tab="members">Members</button>
        </div>
        
        <!-- Calendar Tab -->
        <div class="tab-content active" id="calendar-tab">
            <div class="section-header">
                <h2>Calendar</h2>
                <div class="calendar-nav">
                    <div class="pagination">
                        <a href="{{ route('staff.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="pagination-link prev">
                            <i class="fas fa-chevron-left"></i>
                            <span>Previous</span>
                        </a>
                        <span class="current-month">{{ $currentDate->format('F Y') }}</span>
                        <a href="{{ route('staff.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="pagination-link next">
                            <span>Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <a href="#" class="btn btn-primary" id="addEventBtn">
                        <i class="fas fa-plus"></i> Add Event
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
                                        @if($day['date'])
                                            data-date="{{ $day['date']->format('Y-m-d') }}"
                                        @endif
                                        data-has-events="{{ isset($day['events']) && count($day['events']) > 0 ? 'true' : 'false' }}">
                                        <div class="day-number">{{ $day['day'] }}</div>
                                        @if(isset($day['events']) && count($day['events']) > 0)
                                            <div class="event-indicator">
                                                <span class="event-dot"></span>
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
        
        <!-- Announcements Tab -->
        <div class="tab-content" id="announcements-tab">
            <div class="section-header">
                <h2>Manage Announcements</h2>
                <a href="#" class="btn btn-primary" id="addAnnouncementBtn">
                    <i class="fas fa-plus"></i> Create Announcement
                </a>
            </div>
            <div class="announcements-list">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="announcements-table-body">
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Events Tab -->
        <div class="tab-content" id="events-tab">
            <div class="section-header">
                <h2>Manage Events</h2>
                <a href="#" class="btn btn-primary" id="addEventBtnTab">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            </div>
            <div class="events-list">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="events-table-body">
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Members Tab -->
        <div class="tab-content" id="members-tab">
            <div class="section-header">
                <h2>Member List</h2>
                <div class="search-container">
                    <input type="text" id="member-search" placeholder="Search members...">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="members-list">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="members-table-body">
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
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
                        <a href="#" class="btn btn-primary" id="addEventBtnModal">
                            <i class="fas fa-plus"></i> Add New Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div class="modal" id="eventFormModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="eventFormTitle">Add New Event</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="eventForm" method="POST">
                @csrf
                <input type="hidden" id="event_id" name="event_id">
                <input type="hidden" id="form_method" name="_method" value="POST">
                
                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Event</button>
                    <button type="button" class="btn btn-secondary" id="cancelEventBtn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Announcement Modal -->
<div class="modal" id="announcementFormModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="announcementFormTitle">Add New Announcement</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="announcementForm" method="POST">
                @csrf
                <input type="hidden" id="announcement_id" name="announcement_id">
                <input type="hidden" id="announcement_form_method" name="_method" value="POST">
                
                <div class="form-group">
                    <label for="announcement_title">Title</label>
                    <input type="text" id="announcement_title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="announcement_content">Content</label>
                    <textarea id="announcement_content" name="content" class="form-control" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="announcement_status">Status</label>
                    <select id="announcement_status" name="status" class="form-control">
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                    <button type="button" class="btn btn-secondary" id="cancelAnnouncementBtn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Delete</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
            <div class="form-actions">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                </form>
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
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e6f7ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon i {
        font-size: 1.5rem;
        color: #0066cc;
    }
    
    .stat-info h3 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #666;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #333;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #666;
    }
    
    .management-tabs {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .tabs-header {
        display: flex;
        border-bottom: 1px solid #eee;
    }
    
    .tab-btn {
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .tab-btn.active {
        color: #0066cc;
        border-bottom: 2px solid #0066cc;
    }
    
    .tab-content {
        display: none;
        padding: 1.5rem;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .section-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .pagination {
        display: flex;
        align-items: center;
    }
    
    .pagination-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
    }
    
    .pagination-link:hover {
        background: #f5f5f5;
    }
    
    .current-month {
        font-weight: 600;
        padding: 0 1rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    
    .btn-primary {
        background: #0066cc;
        color: white;
    }
    
    .btn-secondary {
        background: #f5f5f5;
        color: #333;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .calendar {
        margin-top: 1rem;
    }
    
    #cal-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    #cal-table th, 
    #cal-table td {
        border: 1px solid #eee;
        padding: 0.75rem;
        text-align: center;
        vertical-align: top;
    }
    
    .calendar-day {
        height: 80px;
        position: relative;
        cursor: pointer;
    }
    
    .calendar-day:hover {
        background: #f5f5f5;
    }
    
    .day-number {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .today {
        background: #e