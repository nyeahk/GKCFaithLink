<nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top">
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
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown-modern" aria-labelledby="navbarDropdownNotifications" style="background:#ffffff; color:#0f172a; border:1px solid #e5e7eb;">
                            <div class="dropdown-header-modern">
                                <div class="notification-header-content">
                                    <div class="header-left">
                                        <div class="notification-dropdown-title">Notifications</div>
                                        <div class="notification-count-text">
                                            {{ auth()->user()->unreadNotifications->count() }} unread
                                        </div>
                                    </div>
                                    <div class="header-actions">
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                            @csrf
                                                <button type="submit" class="mark-all-btn" title="Mark all as read">
                                                    <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif
                                        <a href="{{ route('notifications.index') }}" class="view-all-btn" title="View all">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            @php
                                $recentNotifications = auth()->user()->notifications()->latest()->take(8)->get()->groupBy(function($n){
                                    return $n->created_at->toDateString();
                                });
                                @endphp
                                
                            <ul class="notification-dropdown-list list-unstyled mb-0">
                                @forelse($recentNotifications as $date => $items)
                                    <li class="dropdown-date-separator"><span>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span></li>
                                    @foreach($items as $notification)
                                        @php
                                            $iconClass = 'secondary'; $icon = 'fas fa-bell';
                                            if ($notification->type == 'App\\Notifications\\DonationApprovedNotification') { $iconClass = 'success'; $icon = 'fas fa-check-circle'; }
                                            elseif ($notification->type == 'App\\Notifications\\DonationDeclinedNotification') { $iconClass = 'danger'; $icon = 'fas fa-times-circle'; }
                                            elseif ($notification->type == 'App\\Notifications\\DonationStatusNotification') {
                                                $status = $notification->data['status'] ?? 'approved';
                                                if ($status === 'declined') { $iconClass = 'danger'; $icon = 'fas fa-times-circle'; } else { $iconClass = 'success'; $icon = 'fas fa-check-circle'; }
                                            }
                                            elseif ($notification->type == 'App\\Notifications\\NewDonationNotification') { $iconClass = 'warning'; $icon = 'fas fa-donate'; }
                                            elseif ($notification->type == 'App\\Notifications\\EventRegistrationNotification') { $iconClass = 'info'; $icon = 'fas fa-calendar-check'; }
                                            elseif ($notification->type == 'App\\Notifications\\EventVolunteerNotification') { $iconClass = 'primary'; $icon = 'fas fa-hands-helping'; }

                                            $title = 'Notification';
                                            if ($notification->type == 'App\\Notifications\\DonationApprovedNotification') { $title = 'Donation Approved'; }
                                            elseif ($notification->type == 'App\\Notifications\\DonationDeclinedNotification') { $title = 'Donation Declined'; }
                                            elseif ($notification->type == 'App\\Notifications\\DonationStatusNotification') { $title = (($notification->data['status'] ?? 'approved') === 'declined') ? 'Donation Declined' : 'Donation Approved'; }
                                            elseif ($notification->type == 'App\\Notifications\\NewDonationNotification') { $title = 'New Donation'; }
                                            elseif ($notification->type == 'App\\Notifications\\EventRegistrationNotification') { $title = 'Event Registration'; }
                                            elseif ($notification->type == 'App\\Notifications\\EventVolunteerNotification') { $title = 'Event Volunteer'; }
                                        @endphp
                                        <li class="notification-dropdown-item">
                                            <a href="{{ route('notifications.show', $notification->id) }}" class="notification-link">
                                                <div class="notification-item-content">
                                                    <div class="notification-icon-wrapper">
                                                        <div class="notification-icon {{ $iconClass }}">
                                                            <i class="{{ $icon }}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="notification-text">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div class="notification-message {{ !$notification->read_at ? 'unread' : '' }}">
                                                                {{ $title }}
                                                    </div>
                                                            @if(!$notification->read_at)
                                                                <span class="unread-dot"></span>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                                            <span class="notification-time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                                            @if(isset($notification->data['amount']))
                                                                <span class="badge bg-success-subtle text-success fw-semibold">₱{{ number_format($notification->data['amount'], 2) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            {{ Str::limit($notification->data['message'] ?? 'You have a new notification', 80) }}
                                                    </div>
                                            </div>
                                        </div>
                                    </a>
                                        </li>
                                    @endforeach
                                @empty
                                    <li class="notification-empty">
                                        <div class="empty-state-mini">
                                            <i class="fas fa-bell-slash"></i>
                                            <span>No notifications yet</span>
                                    </div>
                                    </li>
                                @endforelse
                                <li class="notification-dropdown-footer">
                                    <a href="{{ route('notifications.index') }}" class="view-all-notifications-btn">
                                        View all notifications <i class="fas fa-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <span class="me-3 text-muted">Hi, <strong>{{ Auth::user()->first_name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
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
        border: 1px solid #e5e7eb; /* slate-200 */
        border-radius: 12px;
        box-shadow: 0 12px 28px rgba(17, 24, 39, 0.15); /* subtle slate shadow */
        padding: 0;
        overflow: hidden;
        background: #ffffff;
    }

    /* Header Styles */
    .dropdown-header-modern {
        background: #ffffff;
        color: #0f172a; /* slate-900 */
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e5e7eb;
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
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 0.125rem 0;
        color: #0f172a;
    }

    .notification-count-text {
        font-size: 0.8125rem;
        color: #64748b; /* slate-500 */
        font-weight: 500;
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
        background: #f1f5f9; /* slate-100 */
        border: 1px solid #e5e7eb;
        color: #0f172a;
    }

    .mark-all-btn:hover {
        background: #e2e8f0; /* slate-200 */
        transform: scale(1.1);
        color: #0f172a;
    }

    .view-all-btn {
        background: #2563eb; /* blue-600 */
        border: none;
        color: #ffffff;
    }

    .view-all-btn:hover {
        background: #1d4ed8; /* blue-700 */
        transform: scale(1.1);
        color: #ffffff;
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
        border-bottom: 1px solid #f1f5f9;
    }

    .notification-dropdown-item:last-child {
        border-bottom: none;
    }

    .notification-link {
        display: block;
        padding: 0.875rem 1rem;
        text-decoration: none;
        color: #0f172a;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-link:hover {
        background: #f8fafc; /* slate-50 */
        color: #0f172a;
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
        color: #ffffff;
    }

    .notification-icon.success { background: #22c55e; }   /* green-500 */
    .notification-icon.danger  { background: #ef4444; }   /* red-500   */
    .notification-icon.warning { background: #f59e0b; }   /* amber-500 */
    .notification-icon.info    { background: #0ea5e9; }   /* sky-500   */
    .notification-icon.secondary { background: #9ca3af; } /* gray-400  */

    .notification-text {
        flex: 1;
        min-width: 0;
    }

    .notification-message {
        font-size: 0.9rem;
        font-weight: 500;
        color: #111827; /* gray-900 */
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
        color: #0f172a;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #64748b; /* slate-500 */
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #2563eb; /* blue-600 */
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
        border-top: 1px solid #e5e7eb;
    }

    .view-all-notifications-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.9rem 1rem;
        background: #2563eb; /* blue-600 */
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    .view-all-notifications-btn:hover {
        background: #1d4ed8; /* blue-700 */
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* Custom Scrollbar for Dropdown */
    .notification-dropdown-list::-webkit-scrollbar {
        width: 4px;
    }

    .notification-dropdown-list::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .notification-dropdown-list::-webkit-scrollbar-thumb {
        background: #cbd5e1; /* slate-300 */
        border-radius: 2px;
    }

    .notification-dropdown-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; /* slate-400 */
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

    /* Strong overrides to ensure dropdown uses its own palette, not navbar */
    .dropdown-menu.notification-dropdown-modern,
    .dropdown-menu.notification-dropdown-modern * {
        color: inherit !important;
        text-shadow: none !important;
    }

    .dropdown-menu.notification-dropdown-modern {
        background: #ffffff !important;
    }

    /* Header: soft gray background for contrast */
    .dropdown-header-modern {
        background: #f8fafc !important; /* light gray */
        border-bottom: 1px solid #e5e7eb !important;
    }

    /* Text colors inside dropdown */
    .notification-dropdown-modern,
    .notification-dropdown-modern .notification-link,
    .notification-dropdown-modern .notification-message,
    .notification-dropdown-modern .notification-count-text,
    .notification-dropdown-modern .dropdown-date-separator,
    .notification-dropdown-modern .notification-time {
        color: #0f172a !important; /* slate-900 */
    }

    .notification-dropdown-modern .notification-time,
    .notification-dropdown-modern .dropdown-date-separator {
        color: #64748b !important; /* slate-500 */
    }

    /* Remove underlines and blue link color */
    .notification-dropdown-modern a,
    .notification-dropdown-modern .notification-link {
        text-decoration: none !important;
        color: #0f172a !important;
    }

    .notification-dropdown-modern .notification-link:hover {
        background: #f8fafc !important;
        text-decoration: none !important;
    }

    /* Prevent excessive line-wrapping on words */
    .notification-dropdown-modern .notification-message,
    .notification-dropdown-modern .notification-link,
    .notification-dropdown-modern .notification-time {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }

    /* Icon chips: flat, accessible colors */
    .notification-dropdown-modern .notification-icon.success { background: #22c55e !important; color: #fff !important; }
    .notification-dropdown-modern .notification-icon.danger  { background: #ef4444 !important; color: #fff !important; }
    .notification-dropdown-modern .notification-icon.warning { background: #f59e0b !important; color: #fff !important; }
    .notification-dropdown-modern .notification-icon.info    { background: #0ea5e9 !important; color: #fff !important; }
    .notification-dropdown-modern .notification-icon.secondary{ background: #9ca3af !important; color: #fff !important; }

    /* Date separator clearer */
    .dropdown-date-separator {
        background: #ffffff !important;
        border-bottom: 1px solid #eef2f7 !important;
    }

    /* Action buttons clarity */
    .mark-all-btn { background: #f1f5f9 !important; border: 1px solid #e5e7eb !important; color: #0f172a !important; }
    .mark-all-btn:hover { background: #e2e8f0 !important; }
    .view-all-btn { background: #2563eb !important; color: #ffffff !important; }
    .view-all-btn:hover { background: #1d4ed8 !important; }

    /* Footer button */
    .view-all-notifications-btn { background: #2563eb !important; color: #ffffff !important; }
    .view-all-notifications-btn:hover { background: #1d4ed8 !important; }
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


















