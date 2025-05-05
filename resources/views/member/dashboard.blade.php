@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Member Dashboard</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-700">{{ $carbon->format('F Y') }}</h3>
        </div>
        <div class="p-6">
            <div class="calendar">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <div class="flex space-x-2">
                        <a href="{{ route('member.dashboard', ['month' => $carbon->copy()->subMonth()->format('Y-m')]) }}" 
                           class="px-4 py-2 text-sm bg-white hover:bg-gray-50 text-gray-700 rounded-lg border border-gray-200 shadow-sm transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <a href="{{ route('member.dashboard') }}" 
                           class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Today
                        </a>
                        <a href="{{ route('member.dashboard', ['month' => $carbon->copy()->addMonth()->format('Y-m')]) }}" 
                           class="px-4 py-2 text-sm bg-white hover:bg-gray-50 text-gray-700 rounded-lg border border-gray-200 shadow-sm transition-all flex items-center">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                            Events
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sun</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mon</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tue</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Wed</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thu</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fri</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($weeks as $week)
                                <tr>
                                    @foreach($week as $day)
                                        <td class="px-4 py-4 text-center hover:bg-blue-50 transition-colors {{ $day->month != $carbon->month ? 'text-gray-300 bg-gray-50' : '' }} {{ $day->isToday() ? 'bg-blue-50 font-semibold ring-2 ring-blue-200' : '' }}">
                                            <div class="flex flex-col items-center">
                                                <button type="button" 
                                                        onclick="showEventsModal('{{ $day->format('Y-m-d') }}')"
                                                        class="text-sm {{ $day->isToday() ? 'text-blue-600' : 'text-gray-700' }} hover:text-blue-500 font-medium">
                                                    {{ $day->day }}
                                                </button>
                                                @if(isset($events[$day->format('Y-m-d')]))
                                                    <div class="flex flex-wrap justify-center mt-2 gap-1">
                                                        @foreach($events[$day->format('Y-m-d')] as $event)
                                                            <div class="group relative">
                                                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 hover:scale-110 transition-transform cursor-pointer"></div>
                                                                <div class="absolute bottom-0 left-0 right-0 bg-white text-xs text-gray-700 p-1 rounded shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap">
                                                                    {{ $event->title }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Events Modal -->
<div id="eventsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalDate"></h3>
                        <div id="modalEvents" class="space-y-4">
                            <!-- Events will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeEventsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for overflow */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Animation for calendar */
    .calendar {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .calendar table {
            font-size: 0.75rem;
        }
        
        .calendar td {
            padding: 0.5rem 0.25rem;
        }
    }

    /* Modal Animation */
    .modal-enter {
        opacity: 0;
        transform: scale(0.9);
    }
    
    .modal-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: opacity 300ms, transform 300ms;
    }
    
    .modal-exit {
        opacity: 1;
        transform: scale(1);
    }
    
    .modal-exit-active {
        opacity: 0;
        transform: scale(0.9);
        transition: opacity 300ms, transform 300ms;
    }
</style>

@push('scripts')
<script>
    function showEventsModal(date) {
        const modal = document.getElementById('eventsModal');
        const modalDate = document.getElementById('modalDate');
        const modalEvents = document.getElementById('modalEvents');
        
        // Format the date
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        modalDate.textContent = formattedDate;
        
        // Get events for the selected date
        const events = @json($events);
        const dayEvents = events[date] || [];
        
        // Clear previous events
        modalEvents.innerHTML = '';
        
        if (dayEvents.length === 0) {
            modalEvents.innerHTML = `
                <div class="text-center py-4 text-gray-500">
                    No events scheduled for this date.
                </div>
            `;
        } else {
            dayEvents.forEach(event => {
                const eventCard = document.createElement('div');
                eventCard.className = 'bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow';
                eventCard.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">${event.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${formatTime(event.start_time)} - ${formatTime(event.end_time)}</p>
                            <p class="text-sm text-gray-600 mt-2">${event.description || 'No description available.'}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="registerForEvent(${event.id})" 
                                class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                            Register Now
                        </button>
                    </div>
                `;
                modalEvents.appendChild(eventCard);
            });
        }
        
        modal.classList.remove('hidden');
    }
    
    function closeEventsModal() {
        const modal = document.getElementById('eventsModal');
        modal.classList.add('hidden');
    }
    
    function formatTime(timeString) {
        if (!timeString) return '';
        const date = new Date(timeString);
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    function registerForEvent(eventId) {
        // Send registration request
        fetch(`/member/events/${eventId}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Successfully registered for the event!');
                closeEventsModal();
            } else {
                alert(data.message || 'Failed to register for the event. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while registering for the event. Please try again.');
        });
    }
    
    // Close modal when clicking outside
    document.getElementById('eventsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEventsModal();
        }
    });
    
    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('eventsModal').classList.contains('hidden')) {
            closeEventsModal();
        }
    });
</script>
@endpush
@endsection
