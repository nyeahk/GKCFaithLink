@extends('layouts.member')

@section('title', 'Events')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i> Upcoming Events
                    </h5>
                </div>
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Date & Time</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($event->image_path)
                                                        <div class="me-3">
                                                            <img src="{{ asset('storage/' . $event->image_path) }}" 
                                                                alt="{{ $event->title }}" 
                                                                class="rounded" 
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        </div>
                                                    @else
                                                        <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center" 
                                                            style="width: 50px; height: 50px;">
                                                            <i class="bi bi-calendar-event text-primary" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $event->title }}</h6>
                                                        <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div><i class="bi bi-calendar me-1"></i> {{ $event->start_date->format('M d, Y') }}</div>
                                                    <div><i class="bi bi-clock me-1"></i> {{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><i class="bi bi-geo-alt me-1"></i> {{ $event->location }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.events.show', $event->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye me-1"></i> View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(isset($events) && method_exists($events, 'hasPages') && $events->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
                            </div>
                            <div>
                                {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No Upcoming Events</h5>
                            <p class="text-muted">There are no upcoming events scheduled at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




