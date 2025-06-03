@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2 text-primary"></i> Notifications
                    </h5>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check-double me-1"></i> Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="list-group notification-list">
                        @forelse($notifications as $notification)
                            <a href="{{ route('notifications.show', $notification->id) }}" 
                               class="list-group-item list-group-item-action border-start-0 border-end-0 {{ $notification->read_at ? '' : 'unread' }}">
                                <div class="d-flex align-items-center">
                                    <div class="notification-icon-large me-3">
                                        @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                            <div class="icon-circle bg-success-light">
                                                <i class="fas fa-donate text-success"></i>
                                            </div>
                                        @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                            <div class="icon-circle bg-danger-light">
                                                <i class="fas fa-times-circle text-danger"></i>
                                            </div>
                                        @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                            @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                <div class="icon-circle bg-danger-light">
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                </div>
                                            @else
                                                <div class="icon-circle bg-success-light">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </div>
                                            @endif
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
                                    <div class="flex-grow-1">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 {{ $notification->read_at ? '' : 'fw-bold' }}">
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
                                                @else
                                                    Notification
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 notification-message">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                        @if(isset($notification->data['description']) && !empty($notification->data['description']))
                                            <small class="text-muted">{{ $notification->data['description'] }}</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">You have no notifications.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .notification-list .list-group-item {
        padding: 1rem;
        transition: all 0.2s ease;
    }
    
    .notification-list .list-group-item:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .notification-list .list-group-item.unread {
        background-color: rgba(0, 123, 255, 0.05);
        border-left: 4px solid #0d6efd !important;
    }
    
    .notification-icon-large .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-icon-large i {
        font-size: 1.25rem;
    }
    
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.15);
    }
    
    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.15);
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.15);
    }
    
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.15);
    }
    
    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.15);
    }
    
    .notification-message {
        color: #495057;
    }
    
    .empty-state {
        padding: 2rem;
    }
    
    /* Dropdown styles */
    .notification-dropdown {
        width: 320px;
        padding: 0;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
        border-radius: 0.5rem;
    }
    
    .notification-dropdown .dropdown-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .notification-dropdown .dropdown-item:last-child {
        border-bottom: none;
    }
    
    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush




