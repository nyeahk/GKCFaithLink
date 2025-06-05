@extends('layouts.member')

@section('title', $event->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i> {{ $event->title }}</h5>
                    <a href="{{ route('member.events') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to Events
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
                            @if($event->image_path)
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="img-fluid rounded mb-4">
                            @endif
                            
                            <h4>Event Details</h4>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div>
                                        <strong>Date:</strong><br>
                                        {{ $event->start_date->format('F j, Y') }}
                                        @if(!$event->start_date->isSameDay($event->end_date))
                                            - {{ $event->end_date->format('F j, Y') }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <strong>Time:</strong><br>
                                        {{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary text-white me-3">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <strong>Location:</strong><br>
                                        {{ $event->location }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Description</h5>
                                <div class="event-description">
                                    {!! nl2br(e($event->description)) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Registration</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $registration = $event->getUserRegistration(auth()->id());
                                        $canRegister = !$registration && $event->end_date > now();
                                        $canCancel = $registration && $registration->status != 'cancelled' && $event->start_date > now();
                                    @endphp
                                    
                                    @if($registration)
                                        <div class="alert {{ $registration->status == 'approved' ? 'alert-success' : ($registration->status == 'cancelled' ? 'alert-secondary' : 'alert-info') }}">
                                            <h6 class="alert-heading">
                                                @if($registration->status == 'approved')
                                                    <i class="bi bi-check-circle me-2"></i> Registration Approved
                                                @elseif($registration->status == 'pending')
                                                    <i class="bi bi-hourglass-split me-2"></i> Registration Pending
                                                @elseif($registration->status == 'declined')
                                                    <i class="bi bi-x-circle me-2"></i> Registration Declined
                                                @elseif($registration->status == 'cancelled')
                                                    <i class="bi bi-slash-circle me-2"></i> Registration Cancelled
                                                @endif
                                            </h6>
                                            
                                            <p class="mb-0">You registered on {{ $registration->registration_date->format('F j, Y, g:i a') }}</p>
                                            
                                            @if($registration->is_volunteer)
                                                <div class="mt-2">
                                                    <strong>Volunteer Role:</strong> {{ $registration->volunteer_role }}
                                                </div>
                                            @endif
                                            
                                            @if($registration->notes)
                                                <div class="mt-2">
                                                    <strong>Notes:</strong> {{ $registration->notes }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($canCancel)
                                            <form action="{{ route('member.events.registration.cancel', $event) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to cancel your registration?')">
                                                    <i class="bi bi-x-circle me-2"></i> Cancel Registration
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($event->end_date < now())
                                        <div class="alert alert-secondary">
                                            <i class="bi bi-calendar-x me-2"></i> This event has already ended.
                                        </div>
                                    @else
                                        <p>Would you like to attend this event? Register now!</p>
                                        <a href="{{ route('member.events.registration.create', $event) }}" class="btn btn-primary">
                                            <i class="bi bi-person-plus me-2"></i> Register for this Event
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if($event->start_date > now())
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Volunteer</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($registration && $registration->is_volunteer)
                                            <div class="alert alert-info">
                                                <i class="bi bi-hand-thumbs-up me-2"></i> Thank you for volunteering!
                                            </div>
                                        @elseif($canRegister)
                                            <p>We need volunteers to help make this event a success!</p>
                                            <a href="{{ route('member.events.registration.create', ['event' => $event, 'volunteer' => 1]) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-hand-thumbs-up me-2"></i> Volunteer for this Event
                                            </a>
                                        @elseif($registration && !$registration->is_volunteer && $canCancel)
                                            <p>Would you like to volunteer for this event?</p>
                                            <form action="{{ route('member.events.registration.cancel', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary mb-2" onclick="return confirm('You need to cancel your current registration to register as a volunteer. Continue?')">
                                                    <i class="bi bi-x-circle me-2"></i> Cancel Current Registration
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
