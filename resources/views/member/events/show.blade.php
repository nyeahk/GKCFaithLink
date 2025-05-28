@extends('layouts.member')

@section('title', $event->title)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.events') }}">Events</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $event->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                        {{ $event->title }}
                    </h3>
                    
                    @if($event->image_path)
                        <div class="event-image mb-4">
                            <img src="{{ asset('storage/' . $event->image_path) }}" 
                                alt="{{ $event->title }}" 
                                class="img-fluid rounded">
                        </div>
                    @endif
                    
                    <div class="event-description mb-4">
                        <h5 class="mb-3">Description</h5>
                        <p>{{ $event->description }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="bi bi-calendar text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Date</h6>
                                <p class="mb-0">{{ $event->start_date->format('F d, Y') }}</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="bi bi-clock text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Time</h6>
                                <p class="mb-0">{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="bi bi-geo-alt text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Location</h6>
                                <p class="mb-0">{{ $event->location }}</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <div class="me-3">
                                <i class="bi bi-tag text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Status</h6>
                                <span class="badge bg-success">Published</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('member.events') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i> Back to Events
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection