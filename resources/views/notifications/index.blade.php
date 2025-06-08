@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-bell-fill text-primary me-2"></i> My Notifications
                </h3>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-all me-1"></i> Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            <!-- Success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Notifications -->
            <div class="notifications-container">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ $notification->read_at ? '' : 'unread' }}">
                        <a href="{{ route('notifications.show', $notification->id) }}" class="notification-link">
                            <div class="notification-content">
                                <div class="notification-icon">
                                    @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                        <div class="icon-circle bg-success">
                                            <i class="bi bi-check-circle-fill text-white"></i>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                        <div class="icon-circle bg-danger">
                                            <i class="bi bi-x-circle-fill text-white"></i>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                        @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                            <div class="icon-circle bg-danger">
                                                <i class="bi bi-x-circle-fill text-white"></i>
                                            </div>
                                        @else
                                            <div class="icon-circle bg-success">
                                                <i class="bi bi-check-circle-fill text-white"></i>
                                            </div>
                                        @endif
                                    @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                        <div class="icon-circle bg-warning">
                                            <i class="bi bi-cash-coin text-white"></i>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                        <div class="icon-circle bg-info">
                                            <i class="bi bi-calendar-event-fill text-white"></i>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\EventVolunteerNotification')
                                        <div class="icon-circle bg-primary">
                                            <i class="bi bi-people-fill text-white"></i>
                                        </div>
                                    @else
                                        <div class="icon-circle bg-secondary">
                                            <i class="bi bi-bell-fill text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="notification-details">
                                    <div class="notification-header">
                                        <h5 class="notification-title {{ $notification->read_at ? '' : 'fw-bold' }}">
                                            @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                                Donation Approved
                                            @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                                Donation Declined
                                            @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                                @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                    Donation Declined
                                                @else
                                                    Donation Approved
                                                @endif
                                            @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                                New Donation
                                            @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                                Event Registration
                                            @elseif($notification->type == 'App\Notifications\EventVolunteerNotification')
                                                Event Volunteer
                                            @else
                                                Notification
                                            @endif
                                        </h5>
                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <p class="notification-message">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </p>
                                    
                                    @if(isset($notification->data['description']) && !empty($notification->data['description']))
                                        <div class="notification-description">
                                            {{ $notification->data['description'] }}
                                        </div>
                                    @endif
                                    
                                    @if(!$notification->read_at)
                                        <span class="notification-badge">New</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-bell-slash"></i>
                        </div>
                        <h4>No Notifications</h4>
                        <p>You don't have any notifications at the moment.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="pagination-container mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Container styles */
    .notifications-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    /* Card styles */
    .notification-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .notification-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .notification-card.unread {
        border-left: 4px solid #0d6efd;
    }
    
    .notification-card.unread::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(13, 110, 253, 0.03);
        pointer-events: none;
    }
    
    /* Link styles */
    .notification-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }
    
    /* Content styles */
    .notification-content {
        display: flex;
        padding: 20px;
        gap: 16px;
        align-items: flex-start;
    }
    
    /* Icon styles */
    .notification-icon {
        flex-shrink: 0;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-circle i {
        font-size: 1.5rem;
    }
    
    /* Details styles */
    .notification-details {
        flex-grow: 1;
        min-width: 0; /* Ensures text truncation works */
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }
    
    .notification-title {
        margin: 0;
        font-size: 1.1rem;
        color: #212529;
    }
    
    .notification-time {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
        margin-left: 12px;
    }
    
    .notification-message {
        margin-bottom: 8px;
        color: #495057;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .notification-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    /* Badge styles */
    .notification-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: #0d6efd;
        color: white;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    
    /* Empty state styles */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        color: #343a40;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #6c757d;
        max-width: 300px;
        margin: 0 auto;
    }
    
    /* Pagination styles */
    .pagination-container {
        display: flex;
        justify-content: center;
    }
    
    /* Make sure Bootstrap Icons are loaded */
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css");
</style>
@endpush



