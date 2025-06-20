@extends('layouts.staff')

@section('title', 'Notifications - GKC FaithLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bell-fill me-2"></i> {{ $notification->title }}</h5>
                    <a href="{{ route('staff.notifications') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to Notifications
                     </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            @if($notification->image_path)
                            <img src="{{ asset('storage/' . $notification->image_path) }}" alt="{{ $notification->title }}" class="img-fluid rounded mb-4">
                            @endif
                        </div>

                            
                            <h4>Event Details</h4>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div>
                                        <strong>Date:</strong><br>
                                            @if($notification->event && $notification->event->start_date)
                                                {{ $notification->event->start_date->format('F j, Y') }}
                                                @if($notification->event->end_date && !$notification->event->start_date->isSameDay($notification->event->end_date))
                                                 - {{ $notification->event->end_date->format('F j, Y') }}
                                                @endif
                                            @elseif($notification->created_at)
                                                {{ $notification->created_at->format('F j, Y') }}
                                             @else
                                                <em>No date available</em>
                                            @endif
                                    </div>


                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <strong>Time:</strong><br>
                                        @if($notification->event && $notification->event->start_date)
                                            {{ $notification->event->start_date->format('g:i A') }}
                                            @if($notification->event->end_date)
                                                - {{ $notification->event->end_date->format('g:i A') }}
                                            @endif
                                        @elseif($notification->created_at)
                                            {{ $notification->created_at->format('g:i A') }}
                                        @else
                                            <em>No time available</em>
                                        @endif
                                    </div>

                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <strong>Location:</strong><br>
                                        @if($notification->event && $notification->event->location)
                                            {{ $notification->event->location }}
                                        @else
                                            <em>No location provided</em>
                                        @endif
                                    </div>
 
                                  
    <div class="col-md-6 d-flex align-items-start justify-content-end mb-2">
        <div class="text-end w-100" style="margin-top: -24px;">
            <h5>Description</h5>
            @if($notification->event && $notification->event->description)
                {!! nl2br(e($notification->event->description)) !!}
            @else
                <em>No description available</em>
            @endif
        </div>
    </div>


                                    </div>

                                </div>
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
    .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .event-description {
        white-space: pre-line;
    }
</style>
@endpush