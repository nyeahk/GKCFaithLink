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
                        <i class="bi bi-clock me-1"></i> {{ $notification->created_at->format('F d, Y h:i A') }}
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
                    
                    <!-- Additional information (for all roles) -->
                    @if(isset($notification->data['description']) && !empty($notification->data['description']))
                    <div class="notification-section">
                        <h5 class="section-title">Additional Information</h5>
                        <div class="section-content">
                            {{ $notification->data['description'] }}
                        </div>
                    </div>
                    @endif

                    <!-- Notes (for all roles) -->
                    @if(isset($notification->data['notes']) && !empty($notification->data['notes']))
                    <div class="notification-section">
                        <h5 class="section-title">Notes</h5>
                        <div class="section-content">
                            {{ $notification->data['notes'] }}
                        </div>
                    </div>
                    @endif

                    <!-- Amount (for treasurer and admin only) -->
                    @canany(['isTreasurer', 'isAdmin'])
                        @if(isset($notification->data['amount']))
                        <div class="notification-section">
                            <h5 class="section-title">Amount</h5>
                            <div class="section-content amount">
                                ₱{{ number_format($notification->data['amount'], 2) }}
                            </div>
                        </div>
                        @endif
                    @endcanany

                    <!-- Event details (for staff and member only) -->
                    @canany(['isStaff', 'isMember'])
                        @if(isset($notification->data['event']))
                        <div class="notification-section">
                            <h5 class="section-title">Event Details</h5>
                            <div class="section-content">
                                {{ $notification->data['event'] }}
                            </div>
                        </div>
                        @endif
                    @endcanany

                    <!-- Action button (for all roles, if url exists) -->
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
    body {
        background: #C1E8FF !important;
    }
    .notification-detail-card {
        background-color: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(5, 38, 89, 0.10);
        overflow: hidden;
        margin-bottom: 30px;
        border: 1.5px solid #7DA0CA;
    }
    .notification-header {
        background: linear-gradient(90deg, #5483B3 0%, #7DA0CA 100%);
        padding: 36px 30px 24px 30px;
        text-align: center;
        border-bottom: 1px solid #e3f0fa;
    }
    .notification-icon-large {
        margin-bottom: 18px;
    }
    .notification-icon-large .icon-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(84, 131, 179, 0.15);
        border: 4px solid #fff;
    }
    .notification-icon-large i {
        font-size: 2.8rem;
    }
    .notification-title {
        margin-bottom: 8px;
        font-weight: 700;
        color: #fff;
        font-size: 1.6rem;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 8px rgba(5, 38, 89, 0.10);
    }
    .notification-time {
        color: #e3f0fa;
        font-size: 1rem;
        margin-bottom: 0;
    }
    .notification-body {
        padding: 32px 30px 30px 30px;
    }
    .notification-section {
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e3f0fa;
    }
    .notification-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .section-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #052659;
        margin-bottom: 10px;
    }
    .section-content {
        background-color: #f8f9fa;
        padding: 18px;
        border-radius: 10px;
        font-size: 1.08rem;
        line-height: 1.6;
        color: #343a40;
    }
    .section-content.amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        background: #e3f0fa;
    }
    .notification-action {
        text-align: center;
        margin-top: 28px;
    }
    .btn-outline-primary {
        border-color: #7DA0CA;
        color: #7DA0CA;
        padding: 8px 18px;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s, color 0.2s;
    }
    .btn-outline-primary:hover {
        background-color: #7DA0CA;
        color: #fff;
        border-color: #7DA0CA;
    }
    .btn-primary, .btn-primary:focus, .btn-primary:active, .btn-primary:hover {
        background: #7DA0CA !important;
        border-color: #7DA0CA !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    /* Responsive adjustments */
    @media (max-width: 575.98px) {
        .notification-header, .notification-body {
            padding: 18px 8px;
        }
    }
</style>
@endpush

