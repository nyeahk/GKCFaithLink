// Real-time Notifications Handler
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if user is authenticated and not on auth pages
    if (isUserAuthenticated() && !isAuthPage()) {
        // Initialize real-time notifications
        initializeRealTimeNotifications();

        // Initialize notification count updates
        initializeNotificationCount();
    }
});

function isUserAuthenticated() {
    // Check if there's a CSRF token (indicates authenticated session)
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    return csrfToken !== null;
}

function isAuthPage() {
    // Check if we're on login, register, or other auth pages
    const currentPath = window.location.pathname;
    const authPaths = ['/login', '/register', '/password/reset', '/password/confirm'];
    return authPaths.some(path => currentPath.includes(path));
}

function initializeRealTimeNotifications() {
    // Add a small delay to ensure page is fully loaded and login redirect is complete
    setTimeout(() => {
        // Use fast polling for real-time notifications (simpler approach)
        startPollingFallback();
    }, 2000); // 2 second delay
}

function startSSEConnection() {
    const eventSource = new EventSource('/notifications/stream');
    
    eventSource.onmessage = function(event) {
        const data = JSON.parse(event.data);
        console.log('Real-time notification received:', data);
        showRealTimeNotification(data);
        updateNotificationCount();
    };
    
    eventSource.addEventListener('notification', function(event) {
        const data = JSON.parse(event.data);
        console.log('New notification received:', data);
        showRealTimeNotification(data);
        updateNotificationCount();
    });
    
    eventSource.addEventListener('heartbeat', function(event) {
        console.log('Heartbeat received');
    });
    
    eventSource.onerror = function(event) {
        console.error('SSE connection error:', event);
        // Fallback to polling if SSE fails
        eventSource.close();
        setTimeout(startPollingFallback, 5000);
    };
    
    // Reconnect if connection is lost
    eventSource.addEventListener('error', function(event) {
        if (eventSource.readyState === EventSource.CLOSED) {
            console.log('SSE connection closed, attempting to reconnect...');
            setTimeout(startSSEConnection, 5000);
        }
    });
}

function startPollingFallback() {
    let lastCheck = Math.floor(Date.now() / 1000);

    setInterval(function() {
        // Double-check we're still authenticated before polling
        if (!isUserAuthenticated() || isAuthPage()) {
            return;
        }

        fetch('/notifications/check?last_check=' + lastCheck, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        showRealTimeNotification(notification);
                    });
                    updateNotificationCount();
                }
                lastCheck = data.timestamp || Math.floor(Date.now() / 1000);
            })
            .catch(error => {
                console.error('Polling error:', error);
                // Don't redirect or interfere with page navigation on error
            });
    }, 2000); // Poll every 2 seconds for near real-time experience
}

function showRealTimeNotification(notification) {
    // Create notification toast
    const toast = createNotificationToast(notification);
    
    // Add to page
    document.body.appendChild(toast);
    
    // Show with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        hideNotificationToast(toast);
    }, 5000);
    
    // Update notification bell/badge
    updateNotificationBadge();
}

function createNotificationToast(notification) {
    const toast = document.createElement('div');
    toast.className = 'notification-toast';
    toast.setAttribute('data-notification-id', notification.id);
    
    const iconClass = getNotificationIcon(notification.type);
    const colorClass = getNotificationColor(notification.type);
    
    toast.innerHTML = `
        <div class="notification-toast-content ${colorClass}">
            <div class="notification-toast-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="notification-toast-body">
                <div class="notification-toast-title">${notification.title}</div>
                <div class="notification-toast-message">${notification.message}</div>
                <div class="notification-toast-time">${notification.created_at}</div>
            </div>
            <div class="notification-toast-actions">
                <button class="notification-toast-view" onclick="viewNotification('${notification.url}', '${notification.id}')">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="notification-toast-close" onclick="hideNotificationToast(this.closest('.notification-toast'))">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    `;
    
    return toast;
}

function getNotificationIcon(type) {
    switch(type) {
        case 'volunteer_approved':
            return 'bi bi-check-circle-fill';
        case 'volunteer_declined':
            return 'bi bi-x-circle-fill';
        case 'event_registration':
            return 'bi bi-calendar-plus';
        case 'event_volunteer':
            return 'bi bi-hand-thumbs-up';
        case 'event_created':
            return 'bi bi-calendar-event';
        case 'announcement_created':
            return 'bi bi-megaphone';
        default:
            return 'bi bi-bell-fill';
    }
}

function getNotificationColor(type) {
    switch(type) {
        case 'volunteer_approved':
            return 'notification-success';
        case 'volunteer_declined':
            return 'notification-warning';
        case 'event_registration':
            return 'notification-info';
        case 'event_volunteer':
            return 'notification-primary';
        case 'event_created':
            return 'notification-info';
        case 'announcement_created':
            return 'notification-primary';
        default:
            return 'notification-default';
    }
}

function hideNotificationToast(toast) {
    toast.classList.add('hide');
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}

function viewNotification(url, notificationId) {
    // Mark as read
    markNotificationAsRead(notificationId);
    
    // Navigate to URL
    if (url && url !== '#') {
        window.location.href = url;
    }
}

function markNotificationAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function updateNotificationBadge() {
    // Update the notification badge count
    fetch('/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            const counter = document.querySelector('.notification-counter small span');
            
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = 'flex';
                }
                if (counter) {
                    counter.textContent = data.count + ' unread notifications';
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
                if (counter) {
                    counter.textContent = 'No unread notifications';
                }
            }
        })
        .catch(error => {
            console.error('Error updating notification count:', error);
        });
}

function initializeNotificationCount() {
    // Initial count update
    updateNotificationBadge();
}

// Alias for backward compatibility
function updateNotificationCount() {
    updateNotificationBadge();
}
