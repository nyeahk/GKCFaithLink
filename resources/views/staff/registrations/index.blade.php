{{-- filepath: resources/views/staff/registrations/index.blade.php --}}
@extends('layouts.staff')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-2 mb-4 fw-bold text-primary"><i class="bi bi-person-check me-2"></i>Event Registrations</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Registered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td class="fw-semibold">{{ $registration->id }}</td>
                                    <td>
                                        @if($registration->user)
                                            <img src="{{ $registration->user->profile_photo_url }}"
                                                 alt="{{ $registration->user->name }}"
                                                 class="rounded-circle me-2 border border-2 border-primary-subtle"
                                                 style="width: 36px; height: 36px; object-fit: cover;">
                                            <span class="fw-semibold">{{ $registration->user->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $registration->event->name ?? 'N/A' }}</div>
                                        @if($registration->event)
                                            <div class="small text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $registration->event->date ? \Carbon\Carbon::parse($registration->event->date)->format('M d, Y') : 'Date N/A' }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $registration->event->location ?? 'Location N/A' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-normal">
                                            {{ $registration->created_at ? $registration->created_at->format('Y-m-d H:i') : '' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $registrations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection