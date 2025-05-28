@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Notifications</h5>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-sm btn-outline-primary">
                    Mark all as read
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="list-group">
                @forelse($notifications as $notification)
                    <a href="{{ route('notifications.show', $notification->id) }}" 
                       class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-light' }}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 {{ $notification->read_at ? '' : 'fw-bold' }}">
                                    @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                        <i class="fas fa-donate text-success me-2"></i> Donation Approved
                                    @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                        <i class="fas fa-check-circle text-primary me-2"></i> Donation Status Updated
                                    @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                        <i class="fas fa-hand-holding-usd text-warning me-2"></i> New Donation
                                    @else
                                        <i class="fas fa-bell text-secondary me-2"></i> Notification
                                    @endif
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </h6>
                                <p class="mb-1">{{ $notification->data['description'] ?? '' }}</p>
                            </div>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p>You don't have any notifications yet.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection