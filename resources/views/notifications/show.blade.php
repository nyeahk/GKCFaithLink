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
    /* Card styles */
    .notification-detail-card {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    /* Header styles */
    .notification-header {
        background-color: var(--background-light);
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
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
        background-color: var(--background-light);
        padding: 20px;
        border-radius: 10px;
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
        background-color: var(--background-light);
        color: var(--text-dark);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: var(--hover-transition);
    }
    
    .btn-back:hover {
        background-color: var(--primary-light);
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
</style>
@endpush




