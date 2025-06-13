
{{-- filepath: resources/views/member/events/registrations.blade.php --}}
@extends('layouts.member')

@section('title', 'My Event Registrations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> My Event Registrations</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($registrations->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                            </div>
                            <h5>No Registrations Found</h5>
                            <p class="text-muted">You haven't registered for any events yet.</p>
                            <a href="{{ route('member.events') }}" class="btn btn-primary">
                                <i class="bi bi-calendar-event me-2"></i> Browse Events
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Registration Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td>
                                                <a href="{{ route('member.events.show', $registration->event) }}" class="text-decoration-none">
                                                    {{ $registration->event->title }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $registration->event->start_date->format('M j, Y') }}
                                            </td>
                                            <td>
                                                @if($registration->is_volunteer)
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-hand-thumbs-up me-1"></i> Volunteer
                                                    </span>
                                                    <div class="small text-muted mt-1">
                                                        {{ $registration->volunteerRole->name }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-person-check me-1"></i> Attendee
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($registration->status == 'active')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i> Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('member.events.cancel', $registration) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel your registration?')">
                                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $registrations->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection