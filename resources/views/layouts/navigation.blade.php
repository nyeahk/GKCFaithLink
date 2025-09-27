<<<<<<< Updated upstream
<nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top">
=======
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-dark shadow-sm fixed-top">
>>>>>>> Stashed changes
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ Auth::check() ? route(Auth::user()->getRoleDashboardRoute()) : route('login') }}">
            <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo" class="logo me-2" style="width: 40px; height: 40px; border-radius: 50%;">
            <strong class="text-white">GKC FaithLink</strong>
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
                                            <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 mark-all-read-btn text-primary-dark">
                                                Mark all as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Notification counter -->
<<<<<<< Updated upstream
                            <div class="notification-counter p-2 border-bottom text-center" style="background-color: #e9ecef !important;">
                                <small style="color: #000 !important; font-weight: 600 !important; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-circle {{ auth()->user()->unreadNotifications->count() > 0 ? 'text-primary' : 'text-dark' }} me-1" style="font-size: 8px; color: #000 !important;"></i>
                                    <span style="color: #000 !important;">{{ auth()->user()->unreadNotifications->count() }} unread notifications</span>
=======
                            <div class="notification-counter p-2 bg-background-light border-bottom">
                                <small class="text-muted">
                                    <i class="fas fa-circle {{ auth()->user()->unreadNotifications->count() > 0 ? 'text-primary' : 'text-secondary' }} me-1" style="font-size: 8px;"></i>
                                    <span>{{ auth()->user()->unreadNotifications->count() }} unread notifications</span>
>>>>>>> Stashed changes
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
                                                @elseif($notification->type == 'App\Notifications\EventCreatedNotification')
                                                    <div class="icon-circle bg-success">
                                                        <i class="fas fa-calendar-plus text-white"></i>
                                                    </div>
                                                @elseif($notification->type == 'App\Notifications\AnnouncementCreatedNotification')
                                                    <div class="icon-circle bg-info">
                                                        <i class="fas fa-bullhorn text-white"></i>
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
                                                        @elseif($notification->type == 'App\Notifications\EventCreatedNotification')
                                                            New Event Created
                                                        @elseif($notification->type == 'App\Notifications\AnnouncementCreatedNotification')
                                                            New Announcement
                                                        @else
                                                            Notification
                                                        @endif
                                                    </h6>
                                                    @if(!$notification->read_at)
                                                        <span class="unread-indicator"></span>
                                                    @endif
                                                </div>
                                                <p class="notification-text mb-0 text-text-light small">
                                                    {{ Str::limit($notification->data['message'] ?? 'You have a new notification', 60) }}
                                                </p>
                                                <small class="notification-time text-text-light">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center py-4">
                                        <i class="fas fa-bell-slash text-text-light mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0 text-text-light">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            <!-- Footer with view all link -->
                            <div class="dropdown-footer text-center p-2 border-top">
<<<<<<< Updated upstream
                                @php
                                    // Determine the appropriate notifications route based on user role
                                    $user = auth()->user();
                                    $notificationsRoute = match($user->role) {
                                        4 => 'staff.notifications', // Staff
                                        default => 'notifications.index' // Admin, Treasurer, Member
                                    };
                                @endphp
                                <a href="{{ route($notificationsRoute) }}" class="btn btn-link text-decoration-none w-100">
=======
                                <a href="{{ route('notifications.index') }}" class="btn btn-link text-decoration-none w-100 text-primary">
>>>>>>> Stashed changes
                                    View all notifications <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <span class="me-3 text-white">Hi, <strong>{{ Auth::user()->username }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
<<<<<<< Updated upstream
                    <button type="submit" class="btn btn-outline-light btn-sm">
=======
                    <button type="submit" class="btn btn-outline-white btn-sm">
>>>>>>> Stashed changes
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

@push('styles')
<style>
    /* Standardize ALL navigation headers to #367588 */
    .navbar,
    .top-nav,
    .navigation-header,
    .admin-navbar,
    .member-navbar,
    .treasurer-navbar,
    header.main-header,
    .app-header,
    .site-header,
    nav[class*="navbar"],
    header[class*="navbar"],
    div[class*="navbar"],
    .header-container,
    .main-header,
    .header-wrapper,
    .nav-wrapper,
    .navigation-container {
        background-color: #367588 !important;
        color: #ffffff !important;
        height: 64px !important;
        min-height: 64px !important;
    }
    
    /* Ensure all text in headers is white for consistency */
    .navbar *,
    .top-nav *,
    .navigation-header *,
    .admin-navbar *,
    .member-navbar *,
    .treasurer-navbar *,
    header.main-header *,
    .app-header *,
    .site-header *,
    nav[class*="navbar"] *,
    header[class*="navbar"] *,
    div[class*="navbar"] *,
    .header-container *,
    .main-header *,
    .header-wrapper *,
    .nav-wrapper *,
    .navigation-container *,
    .navbar .text-muted,
    .navbar .text-muted strong,
    .navbar span,
    .navbar strong {
        color: #ffffff !important;
    }
    
    /* Exception for dropdown menus */
    .dropdown-menu,
    .dropdown-menu * {
        color: #333333 !important;
        background-color: #ffffff !important;
    }
    
    /* Special styling for logout buttons */
    .navbar .dropdown-item[href$="logout"],
    form[action$="logout"] button,
    .logout-button,
    button[type="submit"][form*="logout"],
    a[href*="logout"] {
        background-color: transparent !important;
        color: #ffffff !important;
        border: 1px solid #ffffff !important;
        transition: all 0.3s ease !important;
    }

    .navbar .dropdown-item[href$="logout"]:hover,
    form[action$="logout"] button:hover,
    .logout-button:hover,
    button[type="submit"][form*="logout"]:hover,
    a[href*="logout"]:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
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
        color: var(--text-light);
        font-weight: 500;
        list-style: none;
        background-color: var(--background-light);
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
        color: var(--white);
        border-color: var(--white);
    }
    
    /* Notification title in dropdown */
    .notification-title {
        font-size: 0.9rem;
        margin-bottom: 2px;
        color: var(--text-dark);
    }
    
    .notification-title.unread {
        font-weight: 600;
    }

    .notification-mobile-btn:hover {
        background-color: var(--primary-light);
        transform: translateY(-1px);
    }

    /* Desktop Notification Bell */
    .notification-bell {
        position: relative;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        color: var(--white) !important;
    }

    .notification-bell:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: scale(1.05);
        color: var(--white) !important;
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
    }

    .notification-dropdown {
        border-radius: 0.75rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background-color: var(--white);
    }

    .dropdown-header {
        background-color: var(--primary-dark);
        color: var(--white);
    }

    .mark-all-read-btn {
        color: var(--white) !important; /* Ensure visibility against primary-dark background */
        font-weight: 500;
    }

    .mark-all-read-btn:hover {
        text-decoration: underline !important;
    }

    .notification-item.unread {
        background-color: var(--background-light);
    }

    .notification-item:hover {
        background-color: var(--primary-light);
        transition: background-color 0.2s ease;
    }

    .notification-icon .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .unread-indicator {
        width: 8px;
        height: 8px;
        background-color: var(--primary);
        border-radius: 50%;
        margin-left: 0.5rem;
        flex-shrink: 0;
    }

    .dropdown-footer .btn-link {
        color: var(--primary-dark);
        font-weight: 600;
    }

    .dropdown-footer .btn-link:hover {
        color: var(--primary);
    }

    /* Adjustments for text color within the dropdown */
    .notification-text {
        color: var(--text-dark);
    }

    .notification-time {
        color: var(--text-light);
    }

    /* Override Bootstrap primary for custom colors */
    .bg-primary {
        background-color: var(--primary) !important;
    }
    .btn-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .btn-outline-primary {
        color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .text-primary {
        color: var(--primary-dark) !important;
    }

    /* Custom button for logout */
    .btn-outline-white {
        color: var(--white);
        border-color: var(--white);
    }

    .btn-outline-white:hover {
        background-color: var(--white);
        color: var(--primary-dark);
    }

    /* Ensure sufficient contrast for all text elements */
    .text-muted {
        color: var(--text-light) !important;
    }
<<<<<<< Updated upstream

    /* Notification counter styles */
    .notification-counter {
        background-color: #e9ecef !important;
        text-align: center !important;
    }

    .notification-counter small {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #000 !important;
        font-weight: 600 !important;
    }

    .notification-counter small span {
        color: #000 !important;
    }
=======
    
>>>>>>> Stashed changes
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


















