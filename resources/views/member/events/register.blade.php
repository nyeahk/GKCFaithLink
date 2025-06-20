@extends('layouts.member')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Register for Event</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4>{{ $event->title }}</h4>
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="bi bi-calendar-event me-2"></i>
                            {{ $event->start_date->format('F j, Y, g:i a') }} - {{ $event->end_date->format('g:i a') }}
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-geo-alt me-2"></i>
                            {{ $event->location }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('member.events.registration.store', $event) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_volunteer" id="is_volunteer" value="1" {{ request()->has('volunteer') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_volunteer">
                                    I would like to volunteer for this event
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3 volunteer-section" style="{{ request()->has('is_volunteer') ? '' : 'display: none;' }}">
                            <label for="volunteer_role" class="form-label">How would you like to help? <span class="text-danger">*</span></label>
                            <select class="form-select" id="volunteer_role" name="volunteer_role">
                                <option value="">Select a role</option>
                                <option value="setup">Setup</option>
                                <option value="greeting">Greeting</option>
                                <option value="serving">Serving</option>
                                <option value="cleanup">Cleanup</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any dietary restrictions, accessibility needs, or other information we should know?">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('member.events.show', $event) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Complete Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const volunteerCheckbox = document.getElementById('is_volunteer');
        const volunteerSection = document.querySelector('.volunteer-section');
        
        volunteerCheckbox.addEventListener('change', function() {
            if (this.checked) {
                volunteerSection.style.display = 'block';
            } else {
                volunteerSection.style.display = 'none';
            }
        });
    });
</script>
@endpush