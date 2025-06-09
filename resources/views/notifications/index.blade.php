@extends('layouts.app')

@section('title', 'Notifications - GKC FaithLink')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Back button -->
            <div class="mb-3 d-flex">
                <button onclick="goBack()" class="btn back-btn">
                    <i class="bi bi-arrow-left me-2"></i> Back
                </button>
            </div>
            
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-bell-fill text-primary me-2"></i> My Notifications
                </h3>
            </div>
            
            <!-- Mark all as read button -->
            @if(auth()->user()->unreadNotifications->count() > 0)
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-all me-1"></i> Mark all as read
                        </button>
                    </form>
                </div>
            @endif

            <!-- Success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Organized Notifications by Date -->
            <div class="notifications-container">
                @forelse($groupedNotifications as $date => $notificationsForDate)
                    <div class="date-separator">
                        <span>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    </div>
                    
                    @foreach($notificationsForDate as $notification)
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
                                            <span class="notification-time">{{ $notification->created_at->format('h:i A') }}</span>
                                        </div>
                                        
                                        @if(!$notification->read_at)
                                            <span class="notification-badge">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
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
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom back button styling */
    .back-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        color: white;
    }
    
    /* Date separator styling */
    .date-separator {
        position: relative;
        text-align: center;
        margin: 20px 0;
        color: #6c757d;
        font-weight: 500;
    }
    
    .date-separator:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #dee2e6;
        z-index: -1;
    }
    
    .date-separator span {
        background-color: #f8f9fa;
        padding: 0 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        display: inline-block;
    }
    
    /* Notification card styling */
    .notification-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .notification-card.unread {
        border-left: 4px solid #0d6efd;
    }
    
    /* Enhanced hover effect for better readability */
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        background-color: #f8f9fa;
    }
    
    /* Ensure text remains dark and readable on hover */
    .notification-card:hover .notification-title,
    .notification-card:hover .notification-time {
        color: #212529;
        font-weight: 500;
    }
    
    /* Add a subtle transition for text color */
    .notification-title, 
    .notification-time {
        transition: color 0.3s ease, font-weight 0.3s ease;
    }
    
    .notification-link {
        display: block;
        padding: 15px;
        color: inherit;
        text-decoration: none;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
    }
    
    .notification-icon {
        margin-right: 15px;
    }
    
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .notification-details {
        flex: 1;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .notification-title {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .notification-badge {
        display: inline-block;
        background-color: #0d6efd;
        color: white;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 10px;
        margin-top: 5px;
    }
    
    /* Add contrast to the notification badge on hover */
    .notification-card:hover .notification-badge {
        background-color: #0b5ed7;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
</script>
@endpush
@endsection

<!-- Pagination -->
@if(isset($notifications) && method_exists($notifications, 'hasPages') && $notifications->hasPages())
<div class="d-flex justify-content-between align-items-center mt-4">
    <div>
        Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
    </div>
    <div>
        {{ $notifications->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif

