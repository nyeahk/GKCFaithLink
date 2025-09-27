@extends('layouts.staff')

@section('title', 'Event Attendees - ' . $event->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Event Attendees</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">{{ $event->title }} - Attendees</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Events
        </a>
    </div>

    <!-- Event Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <div class="d-flex align-items-center text-muted mb-2">
                        <i class="bi bi-calendar-event me-2"></i>
                        {{ $event->start_date->format('F j, Y, g:i a') }} - {{ $event->end_date->format('g:i a') }}
                    </div>
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-geo-alt me-2"></i>
                        {{ $event->location }}
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-0 text-primary">{{ $regularAttendees->count() }}</div>
                            <small class="text-muted">Attendees</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-success">{{ $approvedVolunteers->count() }}</div>
                            <small class="text-muted">Volunteers</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-warning">{{ $pendingVolunteers->count() }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Notice -->
    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Admin View:</strong> You can view all attendees and volunteers, but only staff members can approve volunteer requests.
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="attendeeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="attendees-tab" data-bs-toggle="tab" data-bs-target="#attendees" type="button" role="tab">
                <i class="bi bi-people me-2"></i>Attendees ({{ $regularAttendees->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="volunteers-tab" data-bs-toggle="tab" data-bs-target="#volunteers" type="button" role="tab">
                <i class="bi bi-hand-thumbs-up me-2"></i>Volunteers ({{ $approvedVolunteers->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <i class="bi bi-clock me-2"></i>Pending Volunteers ({{ $pendingVolunteers->count() }})
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="attendeeTabContent">
        <!-- Regular Attendees Tab -->
        <div class="tab-pane fade show active" id="attendees" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Regular Attendees</h5>
                </div>
                <div class="card-body">
                    @if($regularAttendees->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3">No Attendees Yet</h5>
                            <p class="text-muted">No one has registered for this event yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registration Date</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regularAttendees as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                    </div>
                                                    {{ $registration->user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $registration->user->email }}</td>
                                            <td>{{ $registration->registration_date->format('M j, Y g:i a') }}</td>
                                            <td>
                                                <span class="badge bg-success">Registered</span>
                                            </td>
                                            <td>
                                                @if($registration->notes)
                                                    <span class="text-muted">{{ Str::limit($registration->notes, 50) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
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

        <!-- Approved Volunteers Tab -->
        <div class="tab-pane fade" id="volunteers" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Approved Volunteers</h5>
                </div>
                <div class="card-body">
                    @if($approvedVolunteers->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-hand-thumbs-up" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3">No Volunteers Yet</h5>
                            <p class="text-muted">No volunteers have been approved for this event yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Volunteer Role</th>
                                        <th>Registration Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedVolunteers as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                    </div>
                                                    {{ $registration->user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $registration->user->email }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $registration->volunteer_role)) }}</span>
                                            </td>
                                            <td>{{ $registration->registration_date->format('M j, Y g:i a') }}</td>
                                            <td>
                                                @if($registration->notes)
                                                    <span class="text-muted">{{ Str::limit($registration->notes, 50) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
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

        <!-- Pending Volunteers Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Volunteer Requests</h5>
                </div>
                <div class="card-body">
                    @if($pendingVolunteers->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-clock" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3">No Pending Requests</h5>
                            <p class="text-muted">All volunteer requests have been reviewed.</p>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> Only staff members can approve or decline volunteer requests.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Volunteer Role</th>
                                        <th>Registration Date</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingVolunteers as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                    </div>
                                                    {{ $registration->user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $registration->user->email }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $registration->volunteer_role)) }}</span>
                                            </td>
                                            <td>{{ $registration->registration_date->format('M j, Y g:i a') }}</td>
                                            <td>
                                                @if($registration->notes)
                                                    <span class="text-muted">{{ Str::limit($registration->notes, 50) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Pending Review</span>
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

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #dee2e6;
}
</style>
@endsection
