@extends('layouts.app')

@section('title', 'Notifications - GKC FaithLink')

@section('content')
<div class="notifications-container">
    <!-- Navigation Breadcrumb -->
    <div class="notifications-breadcrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url()->previous() }}" class="back-link">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{
                            auth()->user()->role == 1 ? route('admin.dashboard') :
                            (auth()->user()->role == 2 ? route('treasurer.dashboard') :
                            (auth()->user()->role == 3 ? route('member.dashboard') :
                            (auth()->user()->role == 4 ? route('staff.dashboard') : '/')))
                        }}">
                            <i class="fas fa-home me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-bell me-1"></i>
                        Notifications
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header Section -->
    <div class="notifications-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="header-content">
                        <button onclick="goBack()" class="back-button" title="Go back to previous page">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div class="header-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="header-title">Notifications</h1>
                            <p class="header-subtitle">Stay updated with your latest activities</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="header-actions">
                        <!-- Back Button (Mobile) -->
                        <button onclick="goBack()" class="btn btn-outline-light btn-modern d-md-none me-2">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back
                        </button>

                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-count-badge">
                                {{ auth()->user()->unreadNotifications->count() }} unread
                            </span>
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-modern">
                                    <i class="fas fa-check-double me-2"></i>
                                    Mark All Read
                                </button>
                            </form>
                        @else
                            <span class="all-read-badge">
                                <i class="fas fa-check-circle me-1"></i>
                                All caught up!
                            </span>
                        @endif

                        <!-- Desktop Back Button -->
                        <button onclick="goBack()" class="btn btn-outline-light btn-modern d-none d-md-inline-flex ms-2">
                            <i class="fas fa-arrow-left me-2"></i>
                            Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="notifications-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Filter Tabs -->
            <div class="notification-filters">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">
                        <i class="fas fa-list me-2"></i>All
                        <span class="tab-count">{{ $notifications->total() }}</span>
                    </button>
                    <button class="filter-tab" data-filter="unread">
                        <i class="fas fa-circle me-2"></i>Unread
                        <span class="tab-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </button>
                    <button class="filter-tab" data-filter="donations">
                        <i class="fas fa-donate me-2"></i>Donations
                    </button>
                    <button class="filter-tab" data-filter="events">
                        <i class="fas fa-calendar me-2"></i>Events
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ $notification->read_at ? 'read' : 'unread' }}"
                         data-type="{{ $notification->type }}"
                         onclick="window.location.href='{{ route('notifications.show', $notification->id) }}'">

                        <!-- Notification Icon -->
                        <div class="notification-icon">
                            @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                <div class="icon-wrapper success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                <div class="icon-wrapper danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                    <div class="icon-wrapper danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                @else
                                    <div class="icon-wrapper success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                @endif
                            @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                <div class="icon-wrapper warning">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                <div class="icon-wrapper info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\EventVolunteerNotification')
                                <div class="icon-wrapper primary">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                            @else
                                <div class="icon-wrapper secondary">
                                    <i class="fas fa-bell"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Notification Content -->
                        <div class="notification-content">
                            <div class="notification-header">
                                <h6 class="notification-title">
                                    @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                        <span class="status-badge success">Approved</span> Donation Approved
                                    @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                        <span class="status-badge danger">Declined</span> Donation Declined
                                    @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                        @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                            <span class="status-badge danger">Declined</span> Donation Declined
                                        @else
                                            <span class="status-badge success">Approved</span> Donation Approved
                                        @endif
                                    @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                        <span class="status-badge warning">New</span> New Donation Received
                                    @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                        <span class="status-badge info">Event</span> Event Registration
                                    @elseif($notification->type == 'App\Notifications\EventVolunteerNotification')
                                        <span class="status-badge primary">Volunteer</span> Volunteer Opportunity
                                    @else
                                        <span class="status-badge secondary">Info</span> Notification
                                    @endif
                                </h6>
                                <div class="notification-meta">
                                    <span class="notification-time">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if(!$notification->read_at)
                                        <span class="unread-indicator">
                                            <i class="fas fa-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="notification-body">
                                <p class="notification-message">
                                    {{ $notification->data['message'] ?? 'New notification received' }}
                                </p>
                                @if(isset($notification->data['description']) && !empty($notification->data['description']))
                                    <p class="notification-description">
                                        {{ $notification->data['description'] }}
                                    </p>
                                @endif
                                @if(isset($notification->data['amount']))
                                    <div class="notification-amount">
                                        <i class="fas fa-peso-sign me-1"></i>
                                        <strong>₱{{ number_format($notification->data['amount'], 2) }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Arrow -->
                        <div class="notification-action">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h3 class="empty-title">No notifications yet</h3>
                        <p class="empty-description">
                            When you receive notifications, they'll appear here to keep you updated on important activities.
                        </p>
                        <div class="empty-actions">
                            <button onclick="goBack()" class="btn btn-primary btn-modern me-2">
                                <i class="fas fa-arrow-left me-2"></i>Go Back
                            </button>
                            <a href="{{
                                auth()->user()->role == 1 ? route('admin.dashboard') :
                                (auth()->user()->role == 2 ? route('treasurer.dashboard') :
                                (auth()->user()->role == 3 ? route('member.dashboard') :
                                (auth()->user()->role == 4 ? route('staff.dashboard') : '/')))
                            }}" class="btn btn-outline-primary btn-modern">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="pagination-wrapper">
                    {{ $notifications->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Back Button (Mobile) -->
    <div class="floating-back-btn d-md-none">
        <button onclick="goBack()" class="btn-floating" title="Go back">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern Notifications Styling */
    .notifications-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Breadcrumb Styles */
    .notifications-breadcrumb {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.75rem 0;
    }

    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 0;
    }

    .breadcrumb-item {
        font-size: 0.9rem;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .breadcrumb-item a:hover {
        color: #5a6fd8;
        transform: translateX(-2px);
    }

    .back-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    .back-link:hover {
        transform: translateX(-3px) !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white !important;
    }

    .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 500;
    }

    /* Header Styles */
    .notifications-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Back Button in Header */
    .back-button {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateX(-3px) scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .back-button:active {
        transform: translateX(-2px) scale(0.98);
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-subtitle {
        font-size: 1.1rem;
        margin: 0;
        opacity: 0.9;
        font-weight: 300;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .notification-count-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }

    .all-read-badge {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }

    .btn-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    /* Content Styles */
    .notifications-content {
        padding: 0 0 2rem 0;
    }

    /* Alert Styles */
    .alert-modern {
        border: none;
        border-radius: 15px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        font-size: 1.2rem;
    }

    .alert-content {
        flex: 1;
    }

    /* Filter Tabs */
    .notification-filters {
        margin-bottom: 2rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .filter-tab {
        background: transparent;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        cursor: pointer;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    .filter-tab:hover:not(.active) {
        background: #f8f9fa;
        color: #495057;
    }

    .tab-count {
        background: rgba(255, 255, 255, 0.2);
        color: inherit;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .filter-tab.active .tab-count {
        background: rgba(255, 255, 255, 0.3);
    }

    .filter-tab:not(.active) .tab-count {
        background: #e9ecef;
        color: #6c757d;
    }

    /* Notifications List */
    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .notification-card.unread {
        border-left: 4px solid #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
    }

    .notification-card.unread::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Notification Icon */
    .notification-icon {
        flex-shrink: 0;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        position: relative;
    }

    .icon-wrapper.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .icon-wrapper.danger {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .icon-wrapper.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    .icon-wrapper.info {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .icon-wrapper.primary {
        background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .icon-wrapper.secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    /* Notification Content */
    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        gap: 1rem;
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .status-badge.danger {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        color: white;
    }

    .status-badge.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }

    .status-badge.info {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        color: white;
    }

    .status-badge.primary {
        background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        color: white;
    }

    .status-badge.secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .notification-time {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .unread-indicator {
        color: #667eea;
        font-size: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .notification-body {
        color: #4a5568;
        line-height: 1.6;
    }

    .notification-message {
        font-size: 1rem;
        margin: 0 0 0.5rem 0;
        font-weight: 500;
    }

    .notification-description {
        font-size: 0.9rem;
        color: #718096;
        margin: 0 0 0.75rem 0;
    }

    .notification-amount {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        gap: 0.25rem;
    }

    /* Notification Action */
    .notification-action {
        flex-shrink: 0;
        color: #cbd5e0;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .notification-card:hover .notification-action {
        color: #667eea;
        transform: translateX(3px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #a0aec0;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.75rem;
    }

    .empty-description {
        color: #718096;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .empty-actions .btn {
        min-width: 120px;
    }

    /* Floating Back Button */
    .floating-back-btn {
        position: fixed;
        bottom: 2rem;
        left: 2rem;
        z-index: 1000;
    }

    .btn-floating {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-floating:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }

    .btn-floating:active {
        transform: translateY(-1px) scale(1.05);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper .pagination {
        background: white;
        border-radius: 15px;
        padding: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .pagination-wrapper .page-link {
        border: none;
        color: #667eea;
        font-weight: 500;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: #667eea;
        color: white;
        transform: translateY(-1px);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-breadcrumb {
            padding: 0.5rem 0;
        }

        .breadcrumb-item {
            font-size: 0.8rem;
        }

        .back-link {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .header-title {
            font-size: 2rem;
        }

        .header-subtitle {
            font-size: 1rem;
        }

        .header-actions {
            justify-content: center;
            margin-top: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .back-button {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }

        .filter-tabs {
            justify-content: center;
        }

        .notification-card {
            padding: 1rem;
        }

        .notification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .notification-meta {
            align-self: flex-end;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }

        .floating-back-btn {
            bottom: 1.5rem;
            left: 1.5rem;
        }

        .btn-floating {
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
        }
    }

    @media (max-width: 576px) {
        .notifications-header {
            padding: 1.5rem 0;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .filter-tabs {
            flex-direction: column;
            gap: 0.25rem;
        }

        .filter-tab {
            justify-content: center;
        }

        .notification-card {
            flex-direction: column;
            text-align: center;
        }

        .notification-action {
            align-self: center;
            transform: rotate(90deg);
        }
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Smooth transitions */
    * {
        transition: all 0.3s ease;
    }

    /* Custom scrollbar */
    .notifications-list::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .notifications-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }

    .notifications-list::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }
</style>
@endpush

@push('scripts')
<script>
// Smart back navigation function
function goBack() {
    // Check if user came from within the same site
    const referrer = document.referrer;
    const currentDomain = window.location.origin;

    if (referrer && referrer.startsWith(currentDomain)) {
        // If there's a valid referrer from the same domain, go back
        window.history.back();
    } else {
        // Otherwise, go to appropriate dashboard
        const dashboardUrl = '{{ auth()->user()->role == 1 ? route("admin.dashboard") : (auth()->user()->role == 2 ? route("treasurer.dashboard") : (auth()->user()->role == 3 ? route("member.dashboard") : (auth()->user()->role == 4 ? route("staff.dashboard") : "/"))) }}';
        window.location.href = dashboardUrl;
    }
}

// Add keyboard shortcut for back navigation
document.addEventListener('keydown', function(e) {
    // Alt + Left Arrow or Escape key
    if ((e.altKey && e.key === 'ArrowLeft') || e.key === 'Escape') {
        e.preventDefault();
        goBack();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const notificationCards = document.querySelectorAll('.notification-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Filter notifications
            notificationCards.forEach(card => {
                const cardType = card.dataset.type;
                let show = false;

                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'unread':
                        show = card.classList.contains('unread');
                        break;
                    case 'donations':
                        show = cardType.includes('Donation');
                        break;
                    case 'events':
                        show = cardType.includes('Event');
                        break;
                }

                if (show) {
                    card.style.display = 'flex';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Mark notification as read on click
    notificationCards.forEach(card => {
        card.addEventListener('click', function() {
            if (this.classList.contains('unread')) {
                this.classList.remove('unread');
                this.classList.add('read');

                // Update unread count
                const unreadCount = document.querySelector('.filter-tab[data-filter="unread"] .tab-count');
                if (unreadCount) {
                    const currentCount = parseInt(unreadCount.textContent);
                    unreadCount.textContent = Math.max(0, currentCount - 1);
                }
            }
        });
    });

    // Auto-refresh notifications every 30 seconds
    setInterval(function() {
        // You can implement auto-refresh logic here
        console.log('Auto-refreshing notifications...');
    }, 30000);

    // Add tooltips to back buttons
    const backButtons = document.querySelectorAll('.back-button, .btn-floating');
    backButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.setAttribute('data-bs-toggle', 'tooltip');
            this.setAttribute('data-bs-placement', 'top');
            this.setAttribute('title', 'Go back to previous page (Alt + ← or Esc)');
        });
    });

    // Show success message when going back
    window.addEventListener('beforeunload', function() {
        if (document.referrer) {
            sessionStorage.setItem('returnedFromNotifications', 'true');
        }
    });
});
</script>
@endpush
