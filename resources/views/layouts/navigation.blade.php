<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
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
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown-modern" aria-labelledby="navbarDropdownNotifications">
                            <!-- Header with Quick Actions -->
                            <li class="dropdown-header-modern">
                                <div class="notification-header-content">
                                    <div class="header-left">
                                        <h6 class="notification-dropdown-title">
                                            <i class="fas fa-bell me-2"></i>Notifications
                                        </h6>
                                        <span class="notification-count-text">
                                            @if(auth()->user()->unreadNotifications->count() > 0)
                                                {{ auth()->user()->unreadNotifications->count() }} unread
                                            @else
                                                All caught up!
                                            @endif
                                        </span>
                                    </div>
                                    <div class="header-actions">
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <button class="btn btn-sm btn-outline-primary mark-all-btn"
                                                    onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();"
                                                    title="Mark all as read">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <form id="mark-all-read-form" action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        @endif
                                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary view-all-btn" title="View all notifications">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        
                            <!-- Notifications List -->
                            <div class="notification-dropdown-list">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <li class="notification-dropdown-item">
                                        <a class="notification-link" href="{{ route('notifications.show', $notification->id) }}">
                                            <div class="notification-item-content">
                                                <div class="notification-icon-wrapper">
                                                    @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                                        <div class="notification-icon success">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                    @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                                        <div class="notification-icon danger">
                                                            <i class="fas fa-times-circle"></i>
                                                        </div>
                                                    @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                                        @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                            <div class="notification-icon danger">
                                                                <i class="fas fa-times-circle"></i>
                                                            </div>
                                                        @else
                                                            <div class="notification-icon success">
                                                                <i class="fas fa-check-circle"></i>
                                                            </div>
                                                        @endif
                                                    @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                                        <div class="notification-icon warning">
                                                            <i class="fas fa-hand-holding-usd"></i>
                                                        </div>
                                                    @elseif($notification->type == 'App\Notifications\EventRegistrationNotification')
                                                        <div class="notification-icon info">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </div>
                                                    @else
                                                        <div class="notification-icon secondary">
                                                            <i class="fas fa-bell"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="notification-text">
                                                    <div class="notification-message {{ $notification->read_at ? '' : 'unread' }}">
                                                        {{ $notification->data['message'] ?? 'New notification' }}
                                                    </div>
                                                    <div class="notification-time">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <div class="unread-dot"></div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="notification-empty">
                                        <div class="empty-state-mini">
                                            <i class="fas fa-bell-slash"></i>
                                            <span>No notifications</span>
                                        </div>
                                    </li>
                                @endforelse
                            </div>

                            <!-- Footer with View All Button -->
                            <li class="notification-dropdown-footer">
                                <a href="{{ route('notifications.index') }}" class="view-all-notifications-btn">
                                    <i class="fas fa-list me-2"></i>
                                    View All Notifications
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </li>
                        </ul>
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
    /* Enhanced Notification Dropdown Styles */
    .notification-section {
        position: relative;
    }

    /* Mobile Notification Button */
    .notification-mobile-btn {
        position: relative;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
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
    // Enhanced notification functionality
    const notificationBell = document.getElementById('navbarDropdownNotifications');
    const notificationDropdown = document.querySelector('.notification-dropdown-modern');

    // Add double-click to go to notifications page
    if (notificationBell) {
        let clickCount = 0;
        let clickTimer = null;

        notificationBell.addEventListener('click', function(e) {
            clickCount++;

            if (clickCount === 1) {
                clickTimer = setTimeout(function() {
                    clickCount = 0;
                    // Single click - show dropdown (default behavior)
                }, 300);
            } else if (clickCount === 2) {
                clearTimeout(clickTimer);
                clickCount = 0;
                // Double click - go to notifications page
                e.preventDefault();
                e.stopPropagation();
                window.location.href = '{{ route("notifications.index") }}';
            }
        });
    }

    // Auto-refresh notification count every 30 seconds
    function refreshNotificationCount() {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                const badges = document.querySelectorAll('.notification-badge');
                const countTexts = document.querySelectorAll('.notification-count-text');
                const tabCounts = document.querySelectorAll('.tab-count');

                badges.forEach(badge => {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });

                countTexts.forEach(text => {
                    text.textContent = data.count > 0 ? `${data.count} unread` : 'All caught up!';
                });

                // Update filter tab counts if on notifications page
                if (window.location.pathname.includes('/notifications')) {
                    tabCounts.forEach(count => {
                        if (count.closest('[data-filter="unread"]')) {
                            count.textContent = data.count;
                        }
                    });
                }
            })
            .catch(error => {
                console.log('Error refreshing notification count:', error);
            });
    }

    // Refresh every 30 seconds
    setInterval(refreshNotificationCount, 30000);

    // Mark notification as read when clicked
    document.querySelectorAll('.notification-link').forEach(link => {
        link.addEventListener('click', function() {
            const unreadDot = this.querySelector('.unread-dot');
            if (unreadDot) {
                unreadDot.style.display = 'none';
                // Update count immediately
                setTimeout(refreshNotificationCount, 500);
            }
        });
    });

    // Add loading state to mark all as read button
    const markAllBtn = document.querySelector('.mark-all-btn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
        });
    }

    // Add smooth animations
    const notificationItems = document.querySelectorAll('.notification-dropdown-item');
    notificationItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
        item.classList.add('fade-in-up');
    });
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






