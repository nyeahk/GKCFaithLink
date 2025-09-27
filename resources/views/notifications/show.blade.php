@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Back navigation -->
            <div class="mb-4">
                <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i> Back to Notifications
                </a>
            </div>
            
            <!-- Notification detail card -->
            <div class="notification-detail-card">
                <!-- Header with icon -->
                <div class="notification-header">
                    <div class="notification-icon-large">
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
                    
                    <h3 class="notification-title">
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
                    </h3>
                    
                    <div class="notification-time">
                        <i class="bi bi-clock me-1"></i> {{ $notification->created_at->format('F d, Y g:i A') }}
                    </div>
                </div>
                
                <!-- Notification body -->
                <div class="notification-body">
                    <!-- Main message -->
                    <div class="notification-section">
                        <div class="section-content">
                            {{ $notification->data['message'] ?? 'No message provided.' }}
                        </div>
                    </div>
                    
                    <!-- Additional information -->
                    @if(isset($notification->data['description']) && !empty($notification->data['description']))
                    <div class="notification-section">
                        <h5 class="section-title">Additional Information</h5>
                        <div class="section-content">
                            {{ $notification->data['description'] }}
                        </div>
                    </div>
                    @endif
                    
                    <!-- Notes -->
                    @if(isset($notification->data['notes']) && !empty($notification->data['notes']))
                    <div class="notification-section">
                        <h5 class="section-title">Notes</h5>
                        <div class="section-content">
                            {{ $notification->data['notes'] }}
                        </div>
                    </div>
                    @endif
                    
                    <!-- Amount -->
                    @if(isset($notification->data['amount']))
                    <div class="notification-section">
                        <h5 class="section-title">Amount</h5>
                        <div class="section-content amount">
                            ₱{{ number_format($notification->data['amount'], 2) }}
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action button -->
                    @if(isset($notification->data['url']))
                    <div class="notification-action">
                        <a href="{{ $notification->data['url'] }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-eye me-2"></i> View Details
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Comprehensive override for all buttons and links */
    .btn,
    .btn-primary,
    .btn-lg,
    .notification-action a,
    .notification-action .btn,
    a.btn,
    button.btn,
    .action-btn,
    .view-details,
    .btn-view-details,
    .btn-action {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: white !important;
        transition: all 0.3s ease !important;
    }
    
    /* Hover states for all buttons */
    .btn:hover,
    .btn-primary:hover,
    .btn-lg:hover,
    .notification-action a:hover,
    .notification-action .btn:hover,
    a.btn:hover,
    button.btn:hover,
    .action-btn:hover,
    .view-details:hover,
    .btn-view-details:hover,
    .btn-action:hover {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(79, 149, 157, 0.3) !important;
        background-image: none !important;
    }
    
    /* Target the specific action button in notification detail */
    .notification-action .btn-primary,
    .notification-action .btn-lg,
    .notification-body .btn-primary,
    .notification-body .btn-lg {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    
    .notification-action .btn-primary:hover,
    .notification-action .btn-lg:hover,
    .notification-body .btn-primary:hover,
    .notification-body .btn-lg:hover {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }
    
    /* Override any Bootstrap or custom styles that might be causing purple */
    a:hover,
    button:hover {
        background-color: var(--primary-dark) !important;
        background-image: none !important;
        border-color: var(--primary-dark) !important;
    }
    
    /* Card styles */
    .notification-detail-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    /* Header styles */
    .notification-header {
        background-color: #ffffff; /* pure white header */
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb; /* subtle divider */
    }
    
    .notification-icon-large {
        margin-bottom: 20px;
    }
    
    .notification-icon-large .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .notification-icon-large i {
        font-size: 2.5rem;
    }
    
    .notification-title {
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--text-darker);
    }
    
    .notification-time {
        color: var(--text-light);
        font-size: 0.9rem;
    }
    
    /* Body styles */
    .notification-body {
        padding: 30px;
    }
    
    .notification-section {
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .notification-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
    }
    
    .section-content {
        background-color: #ffffff; /* white sections */
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #eef2f7; /* soft border for structure */
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--text-dark);
    }
    
    .section-content.amount {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
    }
    
    /* Action styles */
    .notification-action {
        text-align: center;
        margin-top: 30px;
    }
    
    .btn-back {
        background-color: #ffffff;
        color: var(--text-dark);
        border: 1px solid #e5e7eb;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: var(--hover-transition);
    }
    
    .btn-back:hover {
        background-color: #f8fafc;
        color: var(--text-darker);
        transform: var(--hover-scale);
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: var(--hover-scale);
    }
    
    /* Icon colors */
    .bg-success {
        background-color: var(--success);
    }
    
    .bg-danger {
        background-color: var(--error);
    }
    
    .bg-warning {
        background-color: var(--warning);
    }
    
    .bg-primary {
        background-color: var(--primary);
    }
    
    /* Back button */
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--text-light);
        text-decoration: none;
        margin-bottom: 20px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .back-link:hover {
        color: var(--primary);
        transform: translateX(-3px);
    }
    
    .back-link i {
        margin-right: 5px;
    }

    /* Layout refinements for fixed-looking content */
    .notification-detail-card { padding: 0; }
    .notification-header { padding: 28px 32px; }
    .notification-title { font-size: 1.25rem; letter-spacing: -0.01em; }
    .notification-body { padding: 24px 24px; }

    .notification-section { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eef2f7; }
    .notification-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }

    .section-title { margin-bottom: 10px; font-size: 0.95rem; }
    .section-content { padding: 16px 18px; border-radius: 12px; border: 1px solid #eaeef5; box-shadow: 0 1px 2px rgba(16,24,40,0.03); }
    .section-content.amount { font-size: 1.375rem; }

    /* Text handling to avoid jank */
    .section-content, .notification-message, .notification-time { word-break: break-word; overflow-wrap: anywhere; }

    /* Button spacing */
    .notification-action { margin-top: 22px; }
    .notification-action .btn { padding: 10px 18px; border-radius: 10px; }

    /* Palette accents from dashboard */
    .notification-detail-card {
        border: 1px solid var(--primary-light, #cfe9e2);
    }

    .notification-header {
        background-color: #ffffff; /* keep white */
        border-bottom: 1px solid var(--primary-light, #cfe9e2);
    }

    .notification-icon-large .icon-circle {
        background-color: var(--primary, #4F959D);
    }

    .notification-title { color: var(--text-darker, #0f172a); }
    .notification-time { color: var(--text-light, #64748b); }

    .notification-section { border-bottom: 1px solid var(--primary-light, #e6f2ef); }

    .section-title { color: var(--primary-dark, #205781); }

    .section-content {
        border: 1px solid var(--primary-light, #e6f2ef);
        box-shadow: 0 1px 2px rgba(79, 149, 157, 0.06);
    }

    .section-content.amount { color: var(--primary-dark, #205781); }

    .btn-primary { background-color: var(--primary, #4F959D); border-color: var(--primary, #4F959D); }
    .btn-primary:hover { background-color: var(--primary-dark, #205781); border-color: var(--primary-dark, #205781); }
</style>
@endpush





