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
            
            <!-- Header with actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="notification-header-icon me-3">
                        <i class="bi bi-bell-fill text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">My Notifications</h3>
                        <p class="text-muted mb-0">Stay updated with your latest activities</p>
                    </div>
                </div>
                
                <div class="notification-actions">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-check-all me-1"></i> Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Filter tabs -->
            <div class="notification-tabs mb-4">
                <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-notifications" type="button" role="tab" aria-controls="all-notifications" aria-selected="true">
                            All
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread-notifications" type="button" role="tab" aria-controls="unread-notifications" aria-selected="false">
                            Unread
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-primary rounded-pill ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter by type
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('notifications.index', ['type' => 'donation']) }}">Donations</a></li>
                            <li><a class="dropdown-item" href="{{ route('notifications.index', ['type' => 'event']) }}">Events</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('notifications.index') }}">All types</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <!-- Notifications content -->
            <div class="tab-content" id="notificationTabsContent">
                <!-- All notifications tab -->
                <div class="tab-pane fade show active" id="all-notifications" role="tabpanel" aria-labelledby="all-tab">
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
                                                
                                                <p class="notification-message">
                                                    {{ $notification->data['message'] ?? 'No message provided.' }}
                                                </p>
                                                
                                                <div class="notification-footer">
                                                    <div class="notification-meta">
                                                        @if(isset($notification->data['amount']))
                                                            <span class="notification-amount">₱{{ number_format($notification->data['amount'], 2) }}</span>
                                                        @endif
                                                        
                                                        @if(!$notification->read_at)
                                                            <span class="notification-badge">New</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="notification-actions">
                                                        <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye me-1"></i> View
                                                        </a>
                                                        
                                                        @if(!$notification->read_at)
                                                            <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="bi bi-check me-1"></i> Mark as read
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
                
                <!-- Unread notifications tab -->
                <div class="tab-pane fade" id="unread-notifications" role="tabpanel" aria-labelledby="unread-tab">
                    <div class="notifications-container">
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications()->latest()->paginate(10);
                            $groupedUnread = collect($unreadNotifications->items())->groupBy(function($notification) {
                                return $notification->created_at->format('Y-m-d');
                            });
                        @endphp
                        
                        @forelse($groupedUnread as $date => $notificationsForDate)
                            <div class="date-separator">
                                <span>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                            </div>
                            
                            @foreach($notificationsForDate as $notification)
                                <div class="notification-card unread">
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
                                                    <h5 class="notification-title fw-bold">
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
                                                
                                                <p class="notification-message">
                                                    {{ $notification->data['message'] ?? 'No message provided.' }}
                                                </p>
                                                
                                                <div class="notification-footer">
                                                    <div class="notification-meta">
                                                        @if(isset($notification->data['amount']))
                                                            <span class="notification-amount">₱{{ number_format($notification->data['amount'], 2) }}</span>
                                                        @endif
                                                        
                                                        <span class="notification-badge">New</span>
                                                    </div>
                                                    
                                                    <div class="notification-actions">
                                                        <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye me-1"></i> View
                                                        </a>
                                                        
                                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-check me-1"></i> Mark as read
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h4>All Caught Up!</h4>
                                <p>You have no unread notifications.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination for unread notifications -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $unreadNotifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Header styling */
    .notification-header-icon {
        font-size: 2rem;
        color: var(--primary);
    }
    
    /* Tabs styling */
    .notification-tabs .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }
    
    .notification-tabs .nav-link {
        color: var(--text-light);
        border: none;
        padding: 0.75rem 1rem;
        font-weight: 500;
        position: relative;
    }
    
    .notification-tabs .nav-link.active {
        color: var(--primary);
        background-color: transparent;
        border-bottom: 2px solid var(--primary);
    }
    
    .notification-tabs .nav-link:hover {
        color: var(--primary-dark);
    }
    
    /* Date separator */
    .date-separator {
        display: flex;
        align-items: center;
        margin: 1.5rem 0 1rem;
        color: var(--text-light);
    }
    
    .date-separator span {
        padding: 0.25rem 0.75rem;
        background-color: var(--background-light);
        border-radius: 1rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    /* Notification card styling */
    .notification-card {
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
        transition: var(--hover-transition);
    }
    
    .notification-card.unread {
        border-left: 4px solid var(--primary);
    }
    
    /* Enhanced hover effect for better readability */
    .notification-card:hover {
        transform: var(--hover-scale);
        box-shadow: var(--hover-shadow);
        background-color: var(--background-light);
    }
    
    /* Ensure text remains dark and readable on hover */
    .notification-card:hover .notification-title,
    .notification-card:hover .notification-time {
        color: var(--text-darker);
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
        align-items: flex-start;
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
        color: var(--text-dark);
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: var(--text-light);
    }
    
    .notification-message {
        margin: 0.5rem 0;
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .notification-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    
    .notification-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-amount {
        font-weight: 600;
        color: var(--success);
    }
    
    .notification-badge {
        display: inline-block;
        background-color: var(--primary);
        color: var(--white);
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 10px;
    }
    
    .notification-actions {
        display: flex;
        gap: 5px;
    }
    
    /* Add contrast to the notification badge on hover */
    .notification-card:hover .notification-badge {
        background-color: var(--primary-dark);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background-color: var(--background-light);
        border-radius: 10px;
        margin: 1rem 0;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .empty-state h4 {
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: var(--text-light);
    }
    
    /* Back button styling */
    .back-btn {
        color: var(--text-light);
        background-color: transparent;
        border: none;
        padding: 0.5rem 0;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .back-btn:hover {
        color: var(--primary);
        transform: translateX(-3px);
    }
    
    /* Button styling to match dashboard */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
    
    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--white);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .notification-content {
            flex-direction: column;
        }
        
        .notification-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .notification-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .notification-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    function goBack() {
        window.history.back();
    }
    
    // Initialize tabs
    document.addEventListener('DOMContentLoaded', function() {
        const tabTriggerList = [].slice.call(document.querySelectorAll('#notificationTabs button'));
        tabTriggerList.forEach(function(tabTriggerEl) {
            tabTriggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                const tab = new bootstrap.Tab(tabTriggerEl);
                tab.show();
            });
        });
        
        // Mark as read functionality
        const markAsReadButtons = document.querySelectorAll('form[action*="mark-as-read"] button');
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const notificationCard = this.closest('.notification-card');
                
                fetch(form.action, {
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
                        notificationCard.classList.remove('unread');
                        const badge = notificationCard.querySelector('.notification-badge');
                        if (badge) badge.remove();
                        
                        // Update unread count in tab
                        const unreadCount = document.querySelector('#unread-tab .badge');
                        if (unreadCount) {
                            const currentCount = parseInt(unreadCount.textContent);
                            if (currentCount > 1) {
                                unreadCount.textContent = currentCount - 1;
                            } else {
                                unreadCount.remove();
                            }
                        }
                        
                        // Remove the button
                        this.closest('.notification-actions').removeChild(form);
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            });
        });
    });
</script>
@endpush


