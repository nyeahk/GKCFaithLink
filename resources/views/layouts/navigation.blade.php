<nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top" style="background-color: #4F959D;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ Auth::check() ? route(Auth::user()->getRoleDashboardRoute()) : route('login') }}">
            <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo" class="logo me-2" style="width: 40px; height: 40px; border-radius: 50%;">
            <strong>GKC FaithLink</strong>
        </a>

        <!-- Right side: Notifications and Logout -->
        <div class="d-flex align-items-center ms-auto">
            @auth
                <!-- Notifications Section -->
                <div class="notification-section d-flex align-items-center me-3">
                    <!-- Direct Notification Link (Mobile) -->
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-light btn-sm d-md-none me-2 notification-mobile-btn">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>

                    <!-- Notifications Dropdown (Desktop) -->
                    <div class="nav-item dropdown d-none d-md-block">
                        <a class="nav-link dropdown-toggle notification-bell" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger rounded-pill notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg p-0" aria-labelledby="navbarDropdownNotifications">
                            <!-- Header with title and actions -->
                            <div class="dropdown-header d-flex justify-content-between align-items-center p-3 border-bottom">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <div class="dropdown-actions">
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 mark-all-read-btn">
                                                Mark all as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Notification counter -->
                            <div class="notification-counter p-2 bg-light border-bottom">
                                <small class="text-muted">
                                    <i class="fas fa-circle {{ auth()->user()->unreadNotifications->count() > 0 ? 'text-primary' : 'text-secondary' }} me-1" style="font-size: 8px;"></i>
                                    <span>{{ auth()->user()->unreadNotifications->count() }} unread notifications</span>
                                </small>
                            </div>
                            
                            <!-- Notifications list -->
                            <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                                @php
                                    $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                                @endphp
                                
                                @forelse($recentNotifications as $notification)
                                    <a href="{{ route('notifications.show', $notification->id) }}" class="dropdown-item notification-item p-0 {{ !$notification->read_at ? 'unread' : '' }}">
                                        <div class="d-flex align-items-center p-3 border-bottom">
                                            <div class="notification-icon me-3">
                                                @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                                    <div class="icon-circle bg-success">
                                                        <i class="fas fa-check-circle text-white"></i>
                                                    </div>
                                                @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                                    <div class="icon-circle bg-danger">
                                                        <i class="fas fa-times-circle text-white"></i>
                                                    </div>
                                                @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                        <div class="icon-circle bg-danger">
                                                            <i class="fas fa-times-circle text-white"></i>
                                                        </div>
                                                    @else
                                                        <div class="icon-circle bg-success">
                                                            <i class="fas fa-check-circle text-white"></i>
                                                        </div>
                                                    @endif
                                                @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                                    <div class="icon-circle bg-warning">
                                                        <i class="fas fa-donate text-white"></i>
                                                    </div>
                                                @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                                    <div class="icon-circle bg-info">
                                                        <i class="fas fa-calendar-check text-white"></i>
                                                    </div>
                                                @elseif($notification->type == 'App\Notifications\EventVolunteerNotification')
                                                    <div class="icon-circle bg-primary">
                                                        <i class="fas fa-hands-helping text-white"></i>
                                                    </div>
                                                @else
                                                    <div class="icon-circle bg-secondary">
                                                        <i class="fas fa-bell text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="notification-content flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="notification-title mb-1 {{ !$notification->read_at ? 'fw-bold' : '' }}">
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
                                                    </h6>
                                                    @if(!$notification->read_at)
                                                        <span class="unread-indicator"></span>
                                                    @endif
                                                </div>
                                                <p class="notification-text mb-0 text-muted small">
                                                    {{ Str::limit($notification->data['message'] ?? 'You have a new notification', 60) }}
                                                </p>
                                                <small class="notification-time text-muted">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center py-4">
                                        <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0 text-muted">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            <!-- Footer with view all link -->
                            <div class="dropdown-footer text-center p-2 border-top">
                                <a href="{{ route('notifications.index') }}" class="btn btn-link text-decoration-none w-100">
                                    View all notifications <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <span class="me-3 text-muted">Hi, <strong>{{ Auth::user()->username }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

@push('styles')
<style>
    /* Update admin navigation header color to #4F959D */
    .navbar {
        background-color: #4F959D !important;
    }
    
    /* Ensure text is visible against the new background */
    .navbar-brand, 
    .navbar-brand strong,
    .navbar .nav-link,
    .navbar .dropdown-toggle,
    .navbar-text,
    .navbar .welcome-text,
    .navbar .user-greeting {
        color: #ffffff !important;
    }
    
    /* Change logout button background and text color to white */
    .navbar .dropdown-item[href$="logout"],
    form[action$="logout"] button {
        color: #ffffff !important;
        background-color: #ffffff !important;
        color: #4F959D !important; /* Text color changed to match navbar for contrast */
    }

    /* Enhanced Notification Dropdown Styles */
    .notification-section {
        position: relative;
    }

    /* Date separator in dropdown */
    .dropdown-date-separator {
        position: relative;
        text-align: center;
        padding: 8px 0;
        margin: 0;
        color: #6c757d;
        font-weight: 500;
        list-style: none;
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
    }
    
    .dropdown-date-separator span {
        font-size: 0.8rem;
        padding: 0 10px;
    }

    /* Mobile Notification Button */
    .notification-mobile-btn {
        position: relative;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    /* Notification title in dropdown */
    .notification-title {
        font-size: 0.9rem;
        margin-bottom: 2px;
        color: #333;
    }
    
    .notification-title.unread {
        font-weight: 600;
    }

    .notification-mobile-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    /* Desktop Notification Bell */
    .notification-bell {
        position: relative;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        color: white !important;
    }

    .notification-bell:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: scale(1.05);
        color: white !important;
    }

    .notification-badge {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        font-size: 0.7rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Modern Dropdown Styles */
    .notification-dropdown-modern {
        width: 380px;
        max-height: 600px;
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 0;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    /* Header Styles */
    .dropdown-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.25rem;
        border: none;
        margin: 0;
    }

    .notification-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .header-left {
        flex: 1;
    }

    .notification-dropdown-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        color: white;
    }

    .notification-count-text {
        font-size: 0.85rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    .mark-all-btn, .view-all-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .mark-all-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .mark-all-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
        color: white;
    }

    .view-all-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: #667eea;
    }

    .view-all-btn:hover {
        background: white;
        transform: scale(1.1);
        color: #667eea;
    }

    /* Notification List Styles */
    .notification-dropdown-list {
        max-height: 400px;
        overflow-y: auto;
        padding: 0;
    }

    .notification-dropdown-item {
        list-style: none;
        margin: 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .notification-dropdown-item:last-child {
        border-bottom: none;
    }

    .notification-link {
        display: block;
        padding: 1rem 1.25rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-link:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        color: inherit;
        text-decoration: none;
    }

    .notification-item-content {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        position: relative;
    }

    .notification-icon-wrapper {
        flex-shrink: 0;
    }

    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: white;
    }

    .notification-icon.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .notification-icon.danger {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    }

    .notification-icon.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .notification-icon.info {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    }

    .notification-icon.secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    .notification-text {
        flex: 1;
        min-width: 0;
    }

    .notification-message {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2d3748;
        margin: 0 0 0.25rem 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .notification-message.unread {
        font-weight: 600;
        color: #1a202c;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #718096;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        animation: pulse 2s infinite;
    }

    /* Empty State */
    .notification-empty {
        list-style: none;
        padding: 2rem 1.25rem;
        text-align: center;
    }

    .empty-state-mini {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: #a0aec0;
    }

    .empty-state-mini i {
        font-size: 2rem;
    }

    .empty-state-mini span {
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Footer Styles */
    .notification-dropdown-footer {
        list-style: none;
        margin: 0;
        padding: 0;
        border-top: 1px solid #f1f3f4;
    }

    .view-all-notifications-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    .view-all-notifications-btn:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* Custom Scrollbar for Dropdown */
    .notification-dropdown-list::-webkit-scrollbar {
        width: 4px;
    }

    .notification-dropdown-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notification-dropdown-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .notification-dropdown-list::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notification-dropdown-modern {
            width: 320px;
            max-height: 500px;
        }

        .notification-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .header-actions {
            align-self: flex-end;
        }
    }

    @media (max-width: 576px) {
        .notification-dropdown-modern {
            width: 280px;
        }

        .notification-link {
            padding: 0.75rem 1rem;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark all as read button functionality
    const markAllReadBtn = document.querySelector('.mark-all-read-btn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            // Add loading state
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Marking...';
            this.disabled = true;
        });
    }
    
    // Auto-refresh notification count every 30 seconds
    function refreshNotificationCount() {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                const counter = document.querySelector('.notification-counter small span');
                const counterIcon = document.querySelector('.notification-counter small i');
                
                if (data.count > 0) {
                    // Update badge
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    }
                    
                    // Update counter
                    if (counter) {
                        counter.textContent = data.count + ' unread notifications';
                    }
                    
                    // Update counter icon
                    if (counterIcon) {
                        counterIcon.classList.remove('text-secondary');
                        counterIcon.classList.add('text-primary');
                    }
                } else {
                    // Hide badge
                    if (badge) {
                        badge.style.display = 'none';
                    }
                    
                    // Update counter
                    if (counter) {
                        counter.textContent = 'No unread notifications';
                    }
                    
                    // Update counter icon
                    if (counterIcon) {
                        counterIcon.classList.remove('text-primary');
                        counterIcon.classList.add('text-secondary');
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing notification count:', error);
            });
    }
    
    // Refresh every 30 seconds
    setInterval(refreshNotificationCount, 30000);
    
    // Mark notification as read when clicked
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const unreadIndicator = this.querySelector('.unread-indicator');
            if (unreadIndicator) {
                unreadIndicator.style.display = 'none';
                this.classList.remove('unread');
                
                // Update count immediately
                setTimeout(refreshNotificationCount, 500);
            }
        });
    });
    
    // Close dropdown when clicking "View all"
    const viewAllLink = document.querySelector('.dropdown-footer a');
    if (viewAllLink) {
        viewAllLink.addEventListener('click', function() {
            const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('navbarDropdownNotifications'));
            if (dropdown) {
                dropdown.hide();
            }
        });
    }
});
</script>

<style>
/* Animation for notification items */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.3s ease forwards;
}

/* Tooltip styles */
.notification-bell[title]:hover::after {
    content: "Double-click to view all notifications";
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 1000;
}
</style>
@endpush














