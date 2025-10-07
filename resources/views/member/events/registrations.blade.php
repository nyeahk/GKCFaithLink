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
                                        <th>Registration Details</th>
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
                                                {{-- Match the UI from registration form --}}
                                                <div class="mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" checked disabled>
                                                        <label class="form-check-label">
                                                            {{ $registration->is_volunteer ? 'I would like to volunteer for this event' : 'Attendee Only' }}
                                                        </label>
                                                    </div>
                                                </div>

                                                @if($registration->is_volunteer)
                                                    <div class="volunteer-section">
                                                        <label class="form-label mb-1">Volunteer Role:</label>
                                                        <select class="form-select form-select-sm" disabled>
                                                            <option value="setup" {{ $registration->volunteer_role == 'setup' ? 'selected' : '' }}>Setup</option>
                                                            <option value="greeting" {{ $registration->volunteer_role == 'greeting' ? 'selected' : '' }}>Greeting</option>
                                                            <option value="serving" {{ $registration->volunteer_role == 'serving' ? 'selected' : '' }}>Serving</option>
                                                            <option value="cleanup" {{ $registration->volunteer_role == 'cleanup' ? 'selected' : '' }}>Cleanup</option>
                                                            <option value="other" {{ $registration->volunteer_role == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($registration->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($registration->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($registration->status === 'declined')
                                                    <span class="badge bg-danger">Declined</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('member.events.cancel', $registration->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this registration?')">
                                                        <i class="bi bi-x-circle"></i> Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection