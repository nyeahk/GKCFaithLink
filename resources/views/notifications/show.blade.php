@php
    // Determine the appropriate layout and routes based on user role
    $user = auth()->user();
    $role = $user->role;
    
    // Set layout based on role
    $layout = match($role) {
        1 => 'layouts.admin',
        2 => 'layouts.treasurer',
        3 => 'layouts.member',
        4 => 'layouts.staff',
        default => 'layouts.app'
    };
    
    // Set back button route based on role
    $notificationsRoute = match($role) {
        1 => 'notifications.index',
        2 => 'notifications.index',
        3 => 'notifications.index',
        4 => 'staff.notifications',
        default => 'notifications.index'
    };
    
    // Helper to get notification icon and color
    function getNotificationAppearance($type) {
        return match (true) {
            str_contains($type, 'DonationApproved') => ['icon' => 'fas fa-check-circle', 'color' => 'text-success'],
            str_contains($type, 'DonationDeclined') => ['icon' => 'fas fa-times-circle', 'color' => 'text-danger'],
            str_contains($type, 'NewDonation') => ['icon' => 'fas fa-hand-holding-usd', 'color' => 'text-warning'],
            str_contains($type, 'EventCreated') => ['icon' => 'fas fa-calendar-plus', 'color' => 'text-info'],
            str_contains($type, 'EventRegistration') => ['icon' => 'fas fa-calendar-check', 'color' => 'text-primary'],
            str_contains($type, 'AnnouncementCreated') => ['icon' => 'fas fa-bullhorn', 'color' => 'text-info'],
            default => ['icon' => 'fas fa-bell', 'color' => 'text-secondary'],
        };
    }

    $appearance = getNotificationAppearance($notification->type);
@endphp

@extends($layout)

@section('title', 'Notification Details')

@section('content')
<div class="notification-details-container">
    <div class="notification-card">
        <div class="card-header">
            <a href="{{ route($notificationsRoute) }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Notifications</span>
            </a>
        </div>
        <div class="card-body">
            <div class="notification-icon">
                <i class="{{ $appearance['icon'] }} {{ $appearance['color'] }}"></i>
            </div>
            <h1 class="notification-title">
                {{ $notification->data['title'] ?? 'Notification' }}
            </h1>
            <p class="notification-message">
                {{ $notification->data['message'] ?? 'No message provided.' }}
            </p>
            <div class="notification-meta">
                <i class="fas fa-clock"></i>
                <span id="notification-time" data-time="{{ $notification->created_at->toIso8601String() }}">
                    {{ $notification->created_at->format('F d, Y, g:i A') }} ({{ $notification->created_at->diffForHumans() }})
                </span>
            </div>

            <hr class="notification-divider">

            <div class="details-list">
                @if(isset($notification->data['amount']))
                    <div class="detail-item">
                        <span class="item-label">Amount</span>
                        <span class="item-value font-weight-bold">₱{{ number_format($notification->data['amount'], 2) }}</span>
                    </div>
                @endif
                @if(isset($notification->data['description']))
                    <div class="detail-item">
                        <span class="item-label">Description</span>
                        <span class="item-value">{{ $notification->data['description'] }}</span>
                    </div>
                @endif
                @if(isset($notification->data['notes']))
                    <div class="detail-item">
                        <span class="item-label">Notes</span>
                        <span class="item-value">{{ $notification->data['notes'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .notification-details-container {
        padding: 2rem 1rem;
        background-color: #f8f9fa;
    }
    .notification-card {
        max-width: 700px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    .back-link:hover {
        color: #343a40;
    }
    .card-body {
        padding: 2.5rem;
        text-align: center;
    }
    .notification-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
    }
    .notification-icon .text-success { color: #28a745 !important; }
    .notification-icon .text-danger { color: #dc3545 !important; }
    .notification-icon .text-warning { color: #ffc107 !important; }
    .notification-icon .text-info { color: #17a2b8 !important; }
    .notification-icon .text-primary { color: #007bff !important; }
    
    .notification-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 0.5rem;
    }
    .notification-message {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    .notification-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-size: 0.9rem;
        background-color: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    .notification-divider {
        margin: 2rem auto;
        border-color: #e9ecef;
        width: 80%;
    }
    .details-list {
        text-align: left;
        margin-bottom: 2rem;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .item-label {
        font-weight: 600;
        color: #495057;
    }
    .item-value {
        color: #6c757d;
    }
    .notification-actions {
        margin-top: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/timeago.js@4.0.2/dist/timeago.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var timeElem = document.getElementById('notification-time');
        if (timeElem) {
            var time = timeElem.getAttribute('data-time');
            function updateTimeago() {
                timeElem.innerHTML = timeago.format(time);
            }
            updateTimeago();
            setInterval(updateTimeago, 60000); // update every minute
        }
    });
</script>
@endpush