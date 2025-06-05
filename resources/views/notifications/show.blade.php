@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2 text-primary"></i> Notification Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notification-detail">
                        <div class="notification-icon-large mb-3">
                            @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                <div class="icon-circle bg-success-light">
                                    <i class="fas fa-donate text-success"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                <div class="icon-circle bg-danger-light">
                                    <i class="fas fa-times-circle text-danger"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                <div class="icon-circle bg-primary-light">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                            @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                <div class="icon-circle bg-warning-light">
                                    <i class="fas fa-hand-holding-usd text-warning"></i>
                                </div>
                            @else
                                <div class="icon-circle bg-secondary-light">
                                    <i class="fas fa-bell text-secondary"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h5 class="mb-3">
                            @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                Donation Approved
                            @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                Donation Declined
                            @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                Donation Status Update
                            @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                New Donation Received
                            @else
                                Notification
                            @endif
                        </h5>
                        
                        <div class="notification-time mb-3 text-muted">
                            <i class="far fa-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                            <span class="ms-2">{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        
                        <div class="notification-message p-3 bg-light rounded mb-3">
                            {{ $notification->data['message'] ?? 'No message provided.' }}
                        </div>
                        
                        @if(isset($notification->data['description']) && !empty($notification->data['description']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Additional Information:</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $notification->data['description'] }}
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($notification->data['notes']) && !empty($notification->data['notes']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Your Notes:</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $notification->data['notes'] }}
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($notification->data['amount']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Amount:</h6>
                            <div class="p-3 bg-light rounded">
                                ₱{{ number_format($notification->data['amount'], 2) }}
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($notification->data['url']))
                        <div class="mt-4">
                            <a href="{{ $notification->data['url'] }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection