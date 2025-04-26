@extends('layouts.gkc')

@section('title', 'Create Event')

@section('content')
    <div class="create-event-container">
        <div class="create-event-header">
            <h1>Create New Event</h1>
            <p class="subtitle">Fill in the details to create a new church event</p>
        </div>

        <form action="{{ route('admin.events.store') }}" method="POST" class="create-event-form" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <div class="location-search-container">
                        <div class="location-input-wrapper">
                            <input type="text" id="location" name="location" class="form-control" required>
                            <div class="location-loading-indicator" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div id="location-suggestions" class="location-suggestions"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="datetime-local" id="start_date" name="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="datetime-local" id="end_date" name="end_date" class="form-control" required>
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Event Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .create-event-container {
        padding: 2rem;
    }

    .create-event-header {
        margin-bottom: 2rem;
    }

    .create-event-header h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .create-event-header .subtitle {
        color: #4a5568;
        margin: 0.5rem 0 0;
        font-size: 1rem;
    }

    .create-event-form {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #2d3748;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    }

    .location-search-container {
        position: relative;
    }

    .location-input-wrapper {
        position: relative;
    }

    .location-loading-indicator {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #4299e1;
    }

    .location-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        margin-top: 0.25rem;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .location-suggestion {
        padding: 0.75rem;
        cursor: pointer;
        transition: background-color 0.2s;
        border-bottom: 1px solid #e2e8f0;
    }

    .location-suggestion:last-child {
        border-bottom: none;
    }

    .location-suggestion:hover {
        background-color: #f7fafc;
    }

    .location-suggestion.active {
        background-color: #ebf8ff;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #2b6cb0;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #cbd5e0;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.getElementById('location');
    const suggestionsContainer = document.getElementById('location-suggestions');
    const loadingIndicator = document.querySelector('.location-loading-indicator');
    let debounceTimer;
    let currentRequest = null;

    locationInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        
        if (query.length < 3) {
            suggestionsContainer.style.display = 'none';
            loadingIndicator.style.display = 'none';
            return;
        }

        // Cancel any ongoing request
        if (currentRequest) {
            currentRequest.abort();
        }

        loadingIndicator.style.display = 'block';
        suggestionsContainer.style.display = 'none';

        debounceTimer = setTimeout(() => {
            // Create new AbortController for this request
            const controller = new AbortController();
            currentRequest = controller;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=ph`, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    suggestionsContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        suggestionsContainer.innerHTML = '<div class="location-suggestion no-results">No locations found</div>';
                        suggestionsContainer.style.display = 'block';
                        return;
                    }

                    data.forEach(location => {
                        const suggestion = document.createElement('div');
                        suggestion.className = 'location-suggestion';
                        suggestion.textContent = location.display_name;
                        
                        suggestion.addEventListener('click', () => {
                            locationInput.value = location.display_name;
                            suggestionsContainer.style.display = 'none';
                        });
                        
                        suggestionsContainer.appendChild(suggestion);
                    });
                    
                    suggestionsContainer.style.display = 'block';
                })
                .catch(error => {
                    if (error.name === 'AbortError') {
                        console.log('Request was aborted');
                    } else {
                        console.error('Error fetching location suggestions:', error);
                        suggestionsContainer.innerHTML = '<div class="location-suggestion error">Error loading suggestions</div>';
                        suggestionsContainer.style.display = 'block';
                    }
                })
                .finally(() => {
                    loadingIndicator.style.display = 'none';
                    currentRequest = null;
                });
        }, 500);
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!locationInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });

    // Handle keyboard navigation
    locationInput.addEventListener('keydown', function(event) {
        const suggestions = suggestionsContainer.querySelectorAll('.location-suggestion:not(.no-results):not(.error)');
        const currentIndex = Array.from(suggestions).findIndex(s => s.classList.contains('active'));
        
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (currentIndex < suggestions.length - 1) {
                suggestions[currentIndex]?.classList.remove('active');
                suggestions[currentIndex + 1].classList.add('active');
                suggestions[currentIndex + 1].scrollIntoView({ block: 'nearest' });
            }
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (currentIndex > 0) {
                suggestions[currentIndex]?.classList.remove('active');
                suggestions[currentIndex - 1].classList.add('active');
                suggestions[currentIndex - 1].scrollIntoView({ block: 'nearest' });
            }
        } else if (event.key === 'Enter') {
            event.preventDefault();
            const activeSuggestion = suggestionsContainer.querySelector('.location-suggestion.active');
            if (activeSuggestion) {
                locationInput.value = activeSuggestion.textContent;
                suggestionsContainer.style.display = 'none';
            }
        }
    });
});
</script>
@endpush 