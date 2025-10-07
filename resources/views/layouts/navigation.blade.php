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

                    <!-- Notifications Link (Desktop) -->
                    <a href="{{ route('notifications.index') }}" class="nav-link notification-bell d-none d-md-block">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
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
 
<!-- Strong inline overrides for notification dropdown to ensure consistent UI and color -->
<style>
    /* Override root primary colors so compiled CSS uses the same blue primary */
    :root {
        /* Aligned with notifications page palette */
        --primary: #4F959D !important; /* original notifications primary */
        --primary-dark: #205781 !important; /* original notifications primary-dark */
        --primary-light: #98D2C0 !important; /* original notifications primary-light */
        --nav-primary: #2563eb !important; /* navigation accent (blue-600) kept for header */
        --nav-primary-dark: #1d4ed8 !important; /* navigation darker */
        --white: #ffffff !important;
        --text-dark: #333333 !important;
        --text-darker: #111111 !important;
        --text-light: #666666 !important;
    }

    /* Ensure dropdown uses a clear white card and dark text */
    .dropdown-menu.notification-dropdown-modern {
        background: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #e5e7eb !important;
        width: 420px !important;
        max-height: 520px !important;
        z-index: 9999 !important;
    }

    /* Links inside dropdown should be dark and not blue */
    .dropdown-menu.notification-dropdown-modern a,
    .dropdown-menu.notification-dropdown-modern .notification-link,
    .dropdown-menu.notification-dropdown-modern .notification-link * {
        color: #0f172a !important;
        text-decoration: none !important;
    }

    /* Neutralize legacy teal/blue backgrounds coming from compiled CSS */
    .dropdown-menu.notification-dropdown-modern .notification-card,
    .dropdown-menu.notification-dropdown-modern .notification-item,
    .dropdown-menu.notification-dropdown-modern .notification-card.border-primary,
    .dropdown-menu.notification-dropdown-modern .notification-item.unread {
        background: transparent !important;
        border-left: none !important;
    }

    /* Badge and unread indicators: choose clear colors */
    .dropdown-menu.notification-dropdown-modern .notification-badge,
    .dropdown-menu.notification-dropdown-modern .badge.notification-badge {
        background: #2563eb !important; /* blue accent */
        color: #fff !important;
    }

    .dropdown-menu.notification-dropdown-modern .unread-dot,
    .dropdown-menu.notification-dropdown-modern .unread-indicator {
        background: #ff4d4f !important; /* red unread */
        box-shadow: 0 0 0 6px rgba(255,77,79,0.06) !important;
    }

    /* Notification icon chips: keep their semantic colors but prefer more saturated tones */
    .dropdown-menu.notification-dropdown-modern .notification-icon.success { background: #16a34a !important; }
    .dropdown-menu.notification-dropdown-modern .notification-icon.danger  { background: #dc2626 !important; }
    .dropdown-menu.notification-dropdown-modern .notification-icon.warning { background: #d97706 !important; }
    .dropdown-menu.notification-dropdown-modern .notification-icon.info    { background: #0284c7 !important; }
    .dropdown-menu.notification-dropdown-modern .notification-icon.secondary{ background: #6b7280 !important; }

    /* Footer button: blue primary */
    .dropdown-menu.notification-dropdown-modern .view-all-notifications-btn,
    .dropdown-menu.notification-dropdown-modern .view-all-btn {
        background: #2563eb !important;
        color: #ffffff !important;
        border: none !important;
    }

    /* Improve spacing and wrapping */
    .dropdown-menu.notification-dropdown-modern .notification-dropdown-item { padding: 0 !important; }
    .dropdown-menu.notification-dropdown-modern .notification-link { padding: 0.9rem 1rem !important; }
    .dropdown-menu.notification-dropdown-modern .notification-message { white-space: normal !important; word-break: break-word !important; }

    /* Small-screen tweaks */
    @media (max-width: 576px) {
        .dropdown-menu.notification-dropdown-modern { width: 300px !important; }
    }
</style>

@push('styles')
<style>
    /* Bright palette for navigation and dropdowns */
    :root {
        /* Use the notifications page palette globally to keep UI consistent */
        --primary: #4F959D; /* main primary from notifications page */
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
        --nav-primary: var(--primary);
        --nav-primary-dark: var(--primary-dark);
        --accent: #ff9f1c; /* keep accent */
        --muted: #4b5563;
    }

    /* Standardize ALL navigation headers to a brighter teal */
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
    background-color: #ffffff !important;
        color: #000000 !important;
        height: 64px !important;
        min-height: 64px !important;
    }
    
    /* Ensure all text in headers is white for consistency */
    /* Only target top-level navigation header elements for white text, avoid cascading into dropdowns */
    .navbar .navbar-brand,
    .navbar .nav-link,
    .navbar .text-muted,
    .navbar span,
    .navbar strong,
    .top-nav .nav-link,
    .navigation-header .nav-link {
        color: #000000 !important;
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
        color: #000000 !important;
        border: 1px solid #000000 !important;
        transition: all 0.3s ease !important;
    }

    .navbar .dropdown-item[href$="logout"]:hover,
    form[action$="logout"] button:hover,
    .logout-button:hover,
    button[type="submit"][form*="logout"]:hover,
    a[href*="logout"]:hover {
        background-color: rgba(0, 0, 0, 0.1) !important;
        color: #000000 !important;
        border-color: #000000 !important;
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
        color: black !important;
    }

    .notification-bell:hover {
        background-color: rgba(255, 255, 255, 0.14);
        transform: scale(1.05);
        color: white !important;
    }

    .notification-badge {
        position: absolute;
        top: 0.2rem;
        right: 0.25rem;
        font-size: 0.75rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ff4d4f; /* brighter red */
        color: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(255,107,107,0.12);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Modern Dropdown Styles */
    /* Stronger, more readable dropdown: wider, lighter, and above other nav styles */
    .dropdown-menu.notification-dropdown-modern {
        width: 420px !important;
        max-height: 560px !important;
        border: 1px solid rgba(34,193,216,0.10) !important; /* soft brighter teal border */
        border-radius: 10px !important;
        box-shadow: 0 18px 40px rgba(16,24,40,0.08) !important; /* subtle soft shadow */
        padding: 0 !important;
        overflow: hidden !important;
        background: #ffffff !important;
        color: #0f172a !important;
        z-index: 2050 !important; /* make sure dropdown sits above other elements */
    }

    /* Compact item layout and hover states */
    .notification-dropdown-item .notification-link {
        padding: 0.9rem 1rem !important;
        border-bottom: 1px solid #eef2f7 !important;
        display: flex !important;
        gap: 0.9rem !important;
        align-items: flex-start !important;
        text-decoration: none !important;
        position: relative !important; /* for unread-dot positioning */
        word-break: break-word !important;
    }

    .notification-dropdown-item .notification-link:hover {
        background: #f8fafc !important;
        transform: translateY(0) !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }

    .notification-icon-wrapper { width: 48px; flex-shrink: 0; display:flex; align-items:flex-start; }
    .notification-text { min-width: 0; }

    .notification-message { font-size: 0.98rem; margin-bottom: 6px; color: #0b2540; line-height:1.3; }
    .notification-message.unread { color: #0b2540; font-weight: 700; }

    /* improved unread indicator: small clear dot inside the list item */
    .unread-dot { position: absolute; right: 14px; top: 18px; width: 9px; height: 9px; background: #ff4d4f; border-radius: 50%; box-shadow: 0 0 0 6px rgba(255,77,79,0.06); }

    .notification-dropdown-list { padding: 0.25rem 0; }

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
        max-height: 420px !important;
        overflow-y: auto !important;
        padding: 0 !important;
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
        display: block !important;
        padding: 0.25rem 0 !important; /* inner padding handled by .notification-dropdown-item .notification-link */
        text-decoration: none !important;
        color: inherit !important;
        transition: background 0.12s ease !important;
        position: relative !important;
    }

    .notification-link:hover {
        background: #f8fafc !important; /* slate-50 */
        color: inherit !important;
        text-decoration: none !important;
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
        font-size: 0.95rem !important;
        font-weight: 500 !important;
        color: #111827 !important; /* gray-900 */
        margin: 0 0 0.25rem 0 !important;
        line-height: 1.35 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        word-break: break-word !important;
    }

    .notification-message.unread {
        font-weight: 700 !important;
        color: #0b2540 !important;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #64748b; /* slate-500 */
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* fallback if unread-indicator class used elsewhere */
    .unread-indicator, .unread-dot { display:inline-block; }

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

<!-- Defensive: final high-specificity override to force notification dropdown palette -->
<style id="notification-dropdown-force" nonce="">
    /* Apply to the dropdown by id/class, its immediate menu and all descendants */
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern,
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern .notification-dropdown-inner,
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern .notification-dropdown-inner * {
        background: #ffffff !important;
        background-image: none !important;
        color: #0f172a !important;
        border-color: #e5e7eb !important;
        box-shadow: 0 18px 40px rgba(16,24,40,0.08) !important;
        -webkit-text-fill-color: #0f172a !important;
    }

    /* Ensure badges and buttons keep the blue accent */
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern .badge,
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern .view-all-btn,
    .dropdown-menu.dropdown-menu-end.notification-dropdown-modern .view-all-notifications-btn {
        background: #2563eb !important;
        color: #fff !important;
    }

    /* Make sure nothing in the dropdown inherits navbar color */
    .navbar .dropdown-menu.notification-dropdown-modern,
    .navbar .dropdown-menu.notification-dropdown-modern * {
        color: inherit !important;
        background: inherit !important;
    }
</style>


















