@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $backRoute = match($user->role) {
        1 => 'admin.dashboard',
        2 => 'treasurer.dashboard',
        3 => 'member.dashboard',
        4 => 'staff.dashboard',
        default => 'dashboard'
    };
@endphp

@include('layouts.navigation')

<div class="notifications-page">
    <div class="notifications-header">
        <div class="container header-content">
            <div class="header-left">
                <button class="back-btn" onclick="location.href='{{ route('notifications.index') }}'" aria-label="Back">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="header-info">
                    <div class="header-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <h1 class="page-title">Notification</h1>
                        <p class="page-subtitle">Details for the selected notification</p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <div class="unread-count">
                    <span class="count-badge">&nbsp;</span>
                    <span class="count-label">&nbsp;</span>
                </div>
            </div>
        </div>
    </div>

    <div class="notifications-content">
        <div class="container">
            <div class="notifications-list">
                <div class="date-group">
                    <div class="date-separator">
                        <div class="date-line"></div>
                        <div class="date-label">Details</div>
                        <div class="date-line"></div>
                    </div>

                    <div class="notification-item" style="cursor:default;">
                        <div class="notification-icon">
                            <div class="icon-wrapper @if($notification->type == 'App\\Notifications\\DonationApprovedNotification' || (isset($notification->data['status']) && ($notification->data['status'] ?? '') == 'approved')) success @elseif($notification->type == 'App\\Notifications\\DonationDeclinedNotification' || (isset($notification->data['status']) && ($notification->data['status'] ?? '') == 'declined')) danger @elseif($notification->type == 'App\\Notifications\\NewDonationNotification') warning @elseif($notification->type == 'App\\Notifications\\EventRegistrationNotification') info @elseif($notification->type == 'App\\Notifications\\EventVolunteerNotification') primary @else secondary @endif">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                        </div>

                        <div class="notification-content">
                            <div class="notification-header">
                                <div>
                                    <h3 class="notification-title">
                                        @if($notification->type == 'App\\Notifications\\DonationApprovedNotification')
                                            Donation Approved
                                        @elseif($notification->type == 'App\\Notifications\\DonationDeclinedNotification')
                                            Donation Declined
                                        @elseif($notification->type == 'App\\Notifications\\DonationStatusNotification')
                                            @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                Donation Declined
                                            @else
                                                Donation Approved
                                            @endif
                                        @elseif($notification->type == 'App\\Notifications\\NewDonationNotification')
                                            New Donation
                                        @elseif($notification->type == 'App\\Notifications\\EventRegistrationNotification')
                                            Event Registration
                                        @elseif($notification->type == 'App\\Notifications\\EventVolunteerNotification')
                                            Event Volunteer
                                        @else
                                            Notification
                                        @endif
                                    </h3>
                                    <p class="notification-message">{{ $notification->data['message'] ?? 'No message provided.' }}</p>
                                </div>

                                <div class="notification-meta">
                                    <div class="notification-time">{{ $notification->created_at->format('F d, Y g:i A') }}</div>
                                    @if(isset($notification->data['amount']))
                                        <div class="notification-amount">₱{{ number_format($notification->data['amount'], 2) }}</div>
                                    @endif
                                </div>
                            </div>

                            @if(isset($notification->data['description']) && !empty($notification->data['description']))
                            <div class="notification-section">
                                <h5 class="section-title">Additional Information</h5>
                                <div class="section-content">{{ $notification->data['description'] }}</div>
                            </div>
                            @endif

                            @if(isset($notification->data['notes']) && !empty($notification->data['notes']))
                            <div class="notification-section">
                                <h5 class="section-title">Notes</h5>
                                <div class="section-content">{{ $notification->data['notes'] }}</div>
                            </div>
                            @endif

                            <div class="notification-actions">
                                <a href="{{ route('notifications.index') }}" class="action-btn secondary">&larr; Back</a>
                                @if(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}" class="action-btn primary">View Details <i class="bi bi-box-arrow-up-right ms-2"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    /* Improved notification detail styles to match index UI and be mobile-friendly */
    .notifications-page { padding-bottom: 48px; }

    .notifications-content .container {
        display: flex;
        justify-content: center;
        padding: 24px 12px;
    }

    .notifications-list { width: 100%; max-width: 820px; }

    .date-group { background: transparent; padding: 0; }

    .notification-item {
        background: #fffef8; /* subtle warm card */
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        display: flex;
        gap: 16px;
        align-items: flex-start;
        border: 1px solid rgba(79,149,157,0.06);
    }

    .notification-icon { flex: 0 0 62px; }
    .icon-wrapper { width: 56px; height: 56px; border-radius: 12px; display:flex; align-items:center; justify-content:center; color: #fff; font-size: 1.25rem; }
    .icon-wrapper.success{ background: linear-gradient(135deg,#28a745,#20c997); }
    .icon-wrapper.danger{ background: linear-gradient(135deg,#dc3545,#e74c3c); }
    .icon-wrapper.warning{ background: linear-gradient(135deg,#ffc107,#f39c12); }
    .icon-wrapper.info{ background: linear-gradient(135deg,#17a2b8,#3498db); }
    .icon-wrapper.primary{ background: linear-gradient(135deg,var(--primary),var(--primary-dark)); }
    .icon-wrapper.secondary{ background: linear-gradient(135deg,#6c757d,#495057); }

    .notification-content { flex: 1 1 auto; min-width: 0; }

    .notification-header { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; }
    .notification-title { margin:0 0 6px; font-size:1.125rem; font-weight:700; color:#0f172a; }
    .notification-message { margin:0 0 8px; color:#334155; font-size:0.98rem; line-height:1.5; }

    .notification-meta { text-align:right; white-space:nowrap; color:#64748b; font-size:0.875rem; }
    .notification-amount { margin-top:6px; color:var(--primary); font-weight:700; }

    .notification-section { margin-top:12px; }
    .section-title { font-size:0.95rem; margin-bottom:8px; color:#0f172a; font-weight:600; }
    .section-content { background:#ffffff; border-radius:10px; padding:12px; border:1px solid #eef2f7; color:#0f172a; }

    .notification-actions { display:flex; gap:12px; margin-top:14px; }
    .action-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:10px; text-decoration:none; font-weight:600; }
    .action-btn.primary { background:var(--primary); color:#fff; border:1px solid var(--primary); }
    .action-btn.secondary { background:#fff; color:#0f172a; border:1px solid #e6eef2; }

    /* Mobile tweaks */
    @media (max-width: 480px) {
        .notification-item { padding: 14px; gap:12px; }
        .notification-meta { text-align:left; margin-top:8px; }
        .notification-header { flex-direction:column; align-items:flex-start; }
        .notifications-content .container { padding-left: 14px; padding-right: 14px; }
    }

    /* Ensure back button matches index style */
    .back-btn { background: transparent; border: 0; color: var(--text-darker, #0f172a); font-size:1rem; padding:6px; margin-right:8px; }
    .back-btn i { font-size:1.1rem; }

</style>
@endpush