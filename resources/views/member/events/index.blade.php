@extends('layouts.member')

@section('title', 'Events')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <!-- Filter tabs -->
            <div class="events-filter-tabs mb-4">
                <a href="{{ route('member.events.index') }}?filter=upcoming"
                   class="filter-tab {{ $filter === 'upcoming' ? 'active' : '' }}">
                    <i class="bi bi-calendar-day"></i> Upcoming
                </a>
                <a href="{{ route('member.events.index') }}?filter=current"
                   class="filter-tab {{ $filter === 'current' ? 'active' : '' }}">
                    <i class="bi bi-play-circle"></i> Happening Now
                </a>
                <a href="{{ route('member.events.index') }}?filter=all"
                   class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
                    <i class="bi bi-calendar"></i> All Events
                </a>
                <a href="{{ route('member.events.index') }}?filter=past"
                   class="filter-tab {{ $filter === 'past' ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Past Events
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        @if($filter === 'upcoming')
                            Upcoming Events
                        @elseif($filter === 'current')
                            Events Happening Now
                        @elseif($filter === 'past')
                            Past Events
                        @else
                            All Events
                        @endif
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
                                        <th>Status</th>
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
                                                <span class="badge {{ $event->time_class ?? 'badge-secondary' }}">
                                                    {{ $event->time_label ?? 'Unknown' }}
                                                </span>
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $events->links() }}
                        </div>
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

@push('styles')
<style>
    /* Filter Tabs */
    .events-filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.5rem;
    }

    .filter-tab {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #6b7280;
        border-radius: 0.375rem 0.375rem 0 0;
        transition: all 0.2s;
        font-weight: 500;
        border-bottom: 2px solid transparent;
    }

    .filter-tab:hover {
        color: #374151;
        background-color: #f9fafb;
        text-decoration: none;
    }

    .filter-tab.active {
        color: #1f2937;
        background-color: #f3f4f6;
        border-bottom-color: #3b82f6;
    }

    .filter-tab i {
        margin-right: 0.5rem;
    }

    /* Time Status Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-primary {
        color: #ffffff;
        background-color: #007bff;
    }

    .badge-success {
        color: #ffffff;
        background-color: #28a745;
    }

    .badge-info {
        color: #ffffff;
        background-color: #17a2b8;
    }

    .badge-secondary {
        color: #ffffff;
        background-color: #6c757d;
    }

    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }
</style>
@endpush




