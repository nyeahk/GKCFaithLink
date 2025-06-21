@extends('layouts.app')

@section('title', 'Notifications - GKC FaithLink')

@section('content')
@php
    // Determine the appropriate back route based on user role
    $user = auth()->user();
    $backRoute = match($user->role) {
        1 => 'admin.dashboard',
        2 => 'treasurer.dashboard',
        3 => 'member.dashboard', 
        4 => 'staff.dashboard',
        default => 'dashboard'
    };
@endphp

<!-- Include role-aware navigation -->
@include('layouts.navigation')

<div class="notifications-page">
    <!-- Header Section -->
    <div class="notifications-header">
        <div class="container">
            <div class="header-content">
                <div class="header-left">
                    <button onclick="goBack('{{ route($backRoute) }}')" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="header-info">
                        <div class="header-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Notifications</h1>
                            <p class="page-subtitle">Stay updated with your latest activities</p>
                        </div>
                    </div>
                </div>

                <div class="header-actions">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-double"></i>
                                <span>Mark all as read</span>
                            </button>
                        </form>
                    @endif
                    <div class="unread-count">
                        <span class="count-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        <span class="count-label">unread</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="notifications-content">
        <div class="container">
            <!-- Filters and Search -->
            <div class="filters-section">
                <div class="filters-container">
                    <!-- Search Bar -->
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="notificationSearch" class="search-input" placeholder="Search notifications...">
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="filter-tabs">
                        <button class="filter-tab active" data-filter="all">
                            <span>All</span>
                            <span class="tab-count">{{ auth()->user()->notifications->count() }}</span>
                        </button>
                        <button class="filter-tab" data-filter="unread">
                            <span>Unread</span>
                            <span class="tab-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                        </button>
                        <button class="filter-tab" data-filter="donation">
                            <span>Donations</span>
                        </button>
                        <button class="filter-tab" data-filter="event">
                            <span>Events</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list" id="notificationsList">
                @forelse($groupedNotifications as $date => $notificationsForDate)
                    <div class="date-group">
                        <div class="date-separator">
                            <div class="date-line"></div>
                            <span class="date-label">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                            <div class="date-line"></div>
                        </div>

                        <div class="notifications-group">
                            @foreach($notificationsForDate as $notification)
                                @php
                                    // Helper functions for notification display
                                    $getNotificationType = function($type) {
                                        if (str_contains($type, 'Donation')) return 'donation';
                                        if (str_contains($type, 'Event')) return 'event';
                                        return 'general';
                                    };

                                    $getNotificationIcon = function($type, $data = []) {
                                        if ($type == 'App\Notifications\DonationApprovedNotification') {
                                            return ['icon' => 'fas fa-check-circle', 'class' => 'success'];
                                        } elseif ($type == 'App\Notifications\DonationDeclinedNotification') {
                                            return ['icon' => 'fas fa-times-circle', 'class' => 'danger'];
                                        } elseif ($type == 'App\Notifications\DonationStatusNotification') {
                                            $status = $data['status'] ?? 'approved';
                                            return $status == 'declined'
                                                ? ['icon' => 'fas fa-times-circle', 'class' => 'danger']
                                                : ['icon' => 'fas fa-check-circle', 'class' => 'success'];
                                        } elseif ($type == 'App\Notifications\NewDonationNotification') {
                                            return ['icon' => 'fas fa-hand-holding-usd', 'class' => 'warning'];
                                        } elseif ($type == 'App\Notifications\EventRegistrationNotification') {
                                            return ['icon' => 'fas fa-calendar-check', 'class' => 'info'];
                                        } elseif ($type == 'App\Notifications\EventVolunteerNotification') {
                                            return ['icon' => 'fas fa-users', 'class' => 'primary'];
                                        } elseif ($type == 'App\Notifications\EventCreatedNotification') {
                                            return ['icon' => 'fas fa-calendar-plus', 'class' => 'success'];
                                        } elseif ($type == 'App\Notifications\AnnouncementCreatedNotification') {
                                            return ['icon' => 'fas fa-bullhorn', 'class' => 'info'];
                                        } else {
                                            return ['icon' => 'fas fa-bell', 'class' => 'secondary'];
                                        }
                                    };

                                    $getNotificationTitle = function($type, $data = []) {
                                        if ($type == 'App\Notifications\DonationApprovedNotification') {
                                            return 'Donation Approved';
                                        } elseif ($type == 'App\Notifications\DonationDeclinedNotification') {
                                            return 'Donation Declined';
                                        } elseif ($type == 'App\Notifications\DonationStatusNotification') {
                                            $status = $data['status'] ?? 'approved';
                                            return $status == 'declined' ? 'Donation Declined' : 'Donation Approved';
                                        } elseif ($type == 'App\Notifications\NewDonationNotification') {
                                            return 'New Donation Received';
                                        } elseif ($type == 'App\Notifications\EventRegistrationNotification') {
                                            return 'Event Registration';
                                        } elseif ($type == 'App\Notifications\EventVolunteerNotification') {
                                            return 'Volunteer Opportunity';
                                        } elseif ($type == 'App\Notifications\EventCreatedNotification') {
                                            return 'New Event Created';
                                        } elseif ($type == 'App\Notifications\AnnouncementCreatedNotification') {
                                            return 'New Announcement';
                                        } else {
                                            return 'Notification';
                                        }
                                    };

                                    $iconData = $getNotificationIcon($notification->type, $notification->data);
                                @endphp

                                <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
                                     data-type="{{ $getNotificationType($notification->type) }}"
                                     data-date="{{ $notification->created_at->format('Y-m-d') }}">

                                    <!-- Unread Indicator -->
                                    @if(!$notification->read_at)
                                        <div class="unread-indicator"></div>
                                    @endif

                                    <!-- Notification Icon -->
                                    <div class="notification-icon">
                                        <div class="icon-wrapper {{ $iconData['class'] }}">
                                            <i class="{{ $iconData['icon'] }}"></i>
                                        </div>
                                    </div>

                                    <!-- Notification Content -->
                                    <div class="notification-content">
                                        <div class="notification-header">
                                            <h3 class="notification-title">
                                                {{ $getNotificationTitle($notification->type, $notification->data) }}
                                            </h3>
                                            <div class="notification-meta">
                                                <span class="notification-time">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                                @if(isset($notification->data['amount']))
                                                    <span class="notification-amount">
                                                        ₱{{ number_format($notification->data['amount'], 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="notification-message">
                                            {{ $notification->data['message'] ?? 'No message provided.' }}
                                        </p>

                                        <!-- Notification Actions -->
                                        <div class="notification-actions">
                                            <a href="{{ route('notifications.show', $notification->id) }}"
                                               class="action-btn primary">
                                                <i class="fas fa-eye"></i>
                                                <span>View Details</span>
                                            </a>

                                            @if(!$notification->read_at)
                                                <button type="button"
                                                        class="action-btn secondary mark-as-read-btn"
                                                        data-notification-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                    <span>Mark as Read</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h3 class="empty-title">No Notifications</h3>
                        <p class="empty-message">You don't have any notifications at the moment.</p>
                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                            <span>Go Back</span>
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <span>Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications</span>
                    </div>
                    <div class="pagination-controls">
                        {{ $notifications->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div class="loading-state" id="loadingState" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading notifications...</p>
        </div>
    </div>
</div>


@endsection

@section('styles')
<style>
    /* Modern Notification Page Styling */
    :root {
        --primary: #4F959D;
        --primary-dark: #205781;
        --primary-light: #98D2C0;
        --background-light: #F6F8D5;
        --white: #ffffff;
        --text-dark: #333333;
        --text-darker: #111111;
        --text-light: #666666;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --info: #17a2b8;
        --secondary: #6c757d;
        --border-light: #e9ecef;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Base Styles */
    .notifications-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Header Section */
    .notifications-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem 0;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .notifications-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        backdrop-filter: blur(10px);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-2px);
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        margin: 0.25rem 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .unread-count {
        text-align: center;
    }

    .count-badge {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .count-label {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Main Content */
    .notifications-content {
        padding: 2rem 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .filters-container {
        padding: 1.5rem;
    }

    /* Search Bar */
    .search-container {
        margin-bottom: 1.5rem;
    }

    .search-input-wrapper {
        position: relative;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        border: 2px solid var(--border-light);
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        transition: var(--transition);
        background: #fafafa;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(79, 149, 157, 0.1);
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .filter-tab:hover:not(.active) {
        background: #e9ecef;
        border-color: var(--border-light);
    }

    .tab-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-tab.active .tab-count {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Notifications List */
    .notifications-list {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* Date Groups */
    .date-group {
        border-bottom: 1px solid var(--border-light);
    }

    .date-group:last-child {
        border-bottom: none;
    }

    .date-separator {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #fafafa;
        border-bottom: 1px solid var(--border-light);
    }

    .date-line {
        flex: 1;
        height: 1px;
        background: var(--border-light);
    }

    .date-label {
        padding: 0 1rem;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Notification Items */
    .notification-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9fa;
        transition: var(--transition);
        cursor: pointer;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: #fafafa;
        transform: translateX(4px);
    }

    .notification-item.unread {
        background: linear-gradient(90deg, rgba(79, 149, 157, 0.05) 0%, transparent 100%);
        border-left: 4px solid var(--primary);
    }

    .notification-item.unread:hover {
        background: linear-gradient(90deg, rgba(79, 149, 157, 0.08) 0%, #fafafa 100%);
    }

    /* Unread Indicator */
    .unread-indicator {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        box-shadow: 0 0 0 2px white;
    }

    /* Notification Icon */
    .notification-icon {
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .icon-wrapper.success { background: linear-gradient(135deg, #28a745, #20c997); }
    .icon-wrapper.danger { background: linear-gradient(135deg, #dc3545, #e74c3c); }
    .icon-wrapper.warning { background: linear-gradient(135deg, #ffc107, #f39c12); }
    .icon-wrapper.info { background: linear-gradient(135deg, #17a2b8, #3498db); }
    .icon-wrapper.primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
    .icon-wrapper.secondary { background: linear-gradient(135deg, #6c757d, #495057); }

    /* Notification Content */
    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
        gap: 1rem;
    }

    .notification-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.4;
    }

    .notification-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .notification-time {
        font-size: 0.75rem;
        color: var(--text-light);
        font-weight: 500;
    }

    .notification-amount {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--success);
        background: rgba(40, 167, 69, 0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
    }

    .notification-message {
        color: var(--text-light);
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0 0 1rem;
    }

    /* Notification Actions */
    .notification-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .action-btn.primary {
        background: var(--primary);
        color: white;
    }

    .action-btn.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .action-btn.secondary {
        background: #f8f9fa;
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .action-btn.secondary:hover {
        background: #e9ecef;
        border-color: var(--text-light);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 0.5rem;
    }

    .empty-message {
        color: var(--text-light);
        font-size: 1rem;
        margin: 0 0 2rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        margin-top: 2rem;
        border-top: 1px solid #e9ecef;
    }
    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .pagination-controls .pagination {
        margin: 0;
    }
    .pagination-controls .page-item .page-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 38px !important;
        min-width: 38px !important;
        padding: 0.25rem 0.75rem !important;
        line-height: 1 !important; /* Aligns text vertically */
    }
    .pagination .page-item.active .page-link {
        font-weight: 600;
    }
    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
    }
    .pagination .page-link:focus {
        box-shadow: none;
    }

    /* Loading State */
    .loading-state {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .loading-spinner {
        text-align: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .loading-state p {
        margin-top: 1rem;
        font-size: 1rem;
        color: #6c757d;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-sm);
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-header {
            padding: 1.5rem 0;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-left {
            flex-direction: column;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .filters-container {
            padding: 1rem;
        }

        .filter-tabs {
            flex-direction: column;
        }

        .notification-item {
            padding: 1rem;
        }

        .notification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .notification-meta {
            align-items: flex-start;
        }

        .notification-actions {
            flex-direction: column;
        }

        .action-btn {
            justify-content: center;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 0.5rem;
        }

        .notifications-content {
            padding: 1rem 0;
        }

        .notification-item {
            padding: 0.75rem;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    /* Override hover styles for buttons and links */
    .btn-primary,
    .btn-view-details,
    .notification-action .btn,
    .view-details-link,
    .action-button {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: var(--white) !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary:hover,
    .btn-view-details:hover,
    .notification-action .btn:hover,
    .view-details-link:hover,
    .action-button:hover {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
        color: var(--white) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(79, 149, 157, 0.3) !important;
    }
    
    /* Override any global hover styles that might be causing purple */
    a:hover,
    button:hover,
    .btn:hover,
    .nav-link:hover {
        background: var(--primary) !important;
        background-color: var(--primary) !important;
        background-image: none !important;
        border-color: var(--primary) !important;
    }
    
    /* Ensure no gradient backgrounds are applied */
    a:hover,
    button:hover,
    .btn:hover {
        background-image: none !important;
        background: var(--primary) !important;
    }

    /* Fix dropdown visibility issues */
    .filter-dropdown {
        position: relative;
    }
    
    .filter-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        min-width: 180px; /* Ensure minimum width */
        width: 100%; /* Make it at least as wide as the button */
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        overflow: visible; /* Ensure content isn't cut off */
    }
    
    /* Ensure dropdown is fully visible */
    .filter-dropdown-menu.show,
    .filter-dropdown-menu[style*="block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        transform: none !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    /* Ensure dropdown items are fully visible */
    .filter-dropdown-menu a {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        clear: both;
        font-weight: 400;
        color: var(--text-dark);
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        text-decoration: none;
    }
    
    .filter-dropdown-menu a:hover {
        background-color: var(--background-light);
        color: var(--primary);
    }
</style>

{{-- Force override for pagination button size --}}
<style>
    .pagination-controls .page-item .page-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 38px !important;
        min-width: 38px !important;
        padding: 0.25rem 0.75rem !important;
        line-height: 1 !important; /* Aligns text vertically */
    }
</style>
@endsection

@push('scripts')
<script>
    // Enhanced Notification Page JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        initializeNotificationPage();
    });

    function goBack(route) {
        window.location.href = route;
    }

    function initializeNotificationPage() {
        initializeSearch();
        initializeFilters();
        initializeMarkAsRead();
        initializeDropdowns();
    }

    // Search functionality
    function initializeSearch() {
        const searchInput = document.getElementById('notificationSearch');
        if (!searchInput) return;

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterNotifications();
            }, 300);
        });
    }

    // Filter functionality
    function initializeFilters() {
        const filterTabs = document.querySelectorAll('.filter-tab');
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active state
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Filter notifications
                filterNotifications();
            });
        });
    }

    // Filter notifications based on search and active filters
    function filterNotifications() {
        const searchTerm = document.getElementById('notificationSearch')?.value.toLowerCase() || '';
        const activeFilter = document.querySelector('.filter-tab.active')?.dataset.filter || 'all';
        const notifications = document.querySelectorAll('.notification-item');
        const dateGroups = document.querySelectorAll('.date-group');

        notifications.forEach(notification => {
            const title = notification.querySelector('.notification-title')?.textContent.toLowerCase() || '';
            const message = notification.querySelector('.notification-message')?.textContent.toLowerCase() || '';
            const type = notification.dataset.type || '';
            const isUnread = notification.classList.contains('unread');

            // Check search match
            const searchMatch = !searchTerm || title.includes(searchTerm) || message.includes(searchTerm);

            // Check filter match
            let filterMatch = true;
            switch(activeFilter) {
                case 'unread':
                    filterMatch = isUnread;
                    break;
                case 'donation':
                    filterMatch = type === 'donation';
                    break;
                case 'event':
                    filterMatch = type === 'event';
                    break;
                case 'all':
                default:
                    filterMatch = true;
                    break;
            }

            // Show/hide notification
            if (searchMatch && filterMatch) {
                notification.style.display = 'flex';
            } else {
                notification.style.display = 'none';
            }
        });

        // Hide empty date groups
        dateGroups.forEach(group => {
            const visibleNotifications = group.querySelectorAll('.notification-item[style*="flex"], .notification-item:not([style*="none"])');
            if (visibleNotifications.length === 0) {
                group.style.display = 'none';
            } else {
                group.style.display = 'block';
            }
        });

        // Show empty state if no notifications visible
        updateEmptyState();
    }

    // Update empty state visibility
    function updateEmptyState() {
        const visibleNotifications = document.querySelectorAll('.notification-item[style*="flex"], .notification-item:not([style*="none"])');
        const emptyState = document.querySelector('.empty-state');
        const notificationsList = document.querySelector('.notifications-list');

        if (visibleNotifications.length === 0 && emptyState) {
            emptyState.style.display = 'block';
            if (notificationsList) notificationsList.style.display = 'none';
        } else {
            if (emptyState) emptyState.style.display = 'none';
            if (notificationsList) notificationsList.style.display = 'block';
        }
    }

    // Mark as read functionality
    function initializeMarkAsRead() {
        const markAsReadButtons = document.querySelectorAll('.mark-as-read-btn');
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const notificationId = this.dataset.notificationId;
                const notificationItem = this.closest('.notification-item');

                markNotificationAsRead(notificationId, notificationItem, this);
            });
        });
    }

    // Mark notification as read via AJAX
    function markNotificationAsRead(notificationId, notificationItem, button) {
        // Show loading state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Marking...</span>';
        button.disabled = true;

        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');

                // Remove unread indicator
                const unreadIndicator = notificationItem.querySelector('.unread-indicator');
                if (unreadIndicator) {
                    unreadIndicator.remove();
                }

                // Remove the button
                button.remove();

                // Update unread counts
                updateUnreadCounts();

                // Show success feedback
                showNotificationFeedback('Notification marked as read', 'success');
            } else {
                throw new Error('Failed to mark as read');
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            showNotificationFeedback('Failed to mark as read', 'error');
        });
    }

    // Update unread counts in UI
    function updateUnreadCounts() {
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        const unreadCount = unreadNotifications.length;

        // Update header count
        const headerCount = document.querySelector('.count-badge');
        if (headerCount) {
            headerCount.textContent = unreadCount;
        }

        // Update filter tab counts
        const unreadTab = document.querySelector('.filter-tab[data-filter="unread"] .tab-count');
        if (unreadTab) {
            unreadTab.textContent = unreadCount;
        }
    }

    // Initialize dropdown functionality
    function initializeDropdowns() {
        const dropdowns = document.querySelectorAll('.filter-dropdown');
        dropdowns.forEach(dropdown => {
            const btn = dropdown.querySelector('.filter-dropdown-btn');
            const menu = dropdown.querySelector('.filter-dropdown-menu');

            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    menu.style.display = 'none';
                });

                // Handle menu item clicks
                const menuItems = menu.querySelectorAll('a');
                menuItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const action = this.dataset.range || this.dataset.sort;
                        handleDropdownAction(action, this);
                        menu.style.display = 'none';
                    });
                });
            }
        });
    }

    // Handle dropdown actions
    function handleDropdownAction(action, element) {
        console.log('Dropdown action:', action);
        // Implement date range and sorting functionality here
        // This would typically involve AJAX calls to update the notification list
    }

    // Show notification feedback
    function showNotificationFeedback(message, type = 'success') {
        // Create feedback element
        const feedback = document.createElement('div');
        feedback.className = `notification-feedback ${type}`;
        feedback.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        `;

        // Add styles
        feedback.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;

        document.body.appendChild(feedback);

        // Animate in
        setTimeout(() => {
            feedback.style.transform = 'translateX(0)';
        }, 100);

        // Remove after delay
        setTimeout(() => {
            feedback.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.parentNode.removeChild(feedback);
                }
            }, 300);
        }, 3000);
    }

    // Handle notification item clicks
    document.addEventListener('click', function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem && !e.target.closest('.notification-actions')) {
            // Navigate to notification detail page
            const viewButton = notificationItem.querySelector('.action-btn.primary');
            if (viewButton) {
                window.location.href = viewButton.href;
            }
        }
    });
</script>
@endpush

<script>
    // Use Bootstrap's built-in dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        })
        
        // Handle dropdown item clicks
        document.querySelectorAll('.dropdown-item[data-range]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const range = this.dataset.range;
                // Update active state
                document.querySelectorAll('.dropdown-item[data-range]').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                // Update button text
                document.getElementById('dateRangeDropdown').innerHTML = 
                    `<i class="fas fa-calendar-alt me-1"></i> ${this.textContent}`;
                // Apply filter
                filterByDateRange(range);
            });
        });
        
        document.querySelectorAll('.dropdown-item[data-sort]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const sort = this.dataset.sort;
                // Update active state
                document.querySelectorAll('.dropdown-item[data-sort]').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                // Update button text
                document.getElementById('sortDropdown').innerHTML = 
                    `<i class="fas fa-sort me-1"></i> ${this.textContent}`;
                // Apply sorting
                sortNotifications(sort);
            });
        });
    });
    
    // Filter notifications by date range
    function filterByDateRange(range) {
        console.log('Filtering by date range:', range);
        // Implement your filtering logic here
        filterNotifications();
    }
    
    // Sort notifications
    function sortNotifications(sort) {
        console.log('Sorting notifications:', sort);
        // Implement your sorting logic here
        filterNotifications();
    }
</script>




