@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 md:mb-0">Events</h1>
        <div class="flex space-x-3">
            <a href="{{ route('member.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#205781] text-white rounded-lg hover:bg-[#4F959D] transition-colors shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Events Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button class="tab-btn active border-b-2 border-[#205781] text-[#205781] whitespace-nowrap py-4 px-1 text-sm font-medium" data-tab="upcoming">
                    Upcoming Events
                </button>
                <button class="tab-btn border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 text-sm font-medium" data-tab="completed">
                    Completed Events
                </button>
            </nav>
        </div>
    </div>

    <!-- Upcoming Events Grid -->
    <div class="events-grid" id="upcoming-events">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Sample Event 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#205781] text-white rounded-lg p-3 text-center mr-4">
                            <div class="text-sm font-semibold">JAN</div>
                            <div class="text-xl font-bold">15</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Sunday Service</h3>
                            <p class="text-sm text-gray-500">9:00 AM - 11:00 AM</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">Join us for our weekly Sunday service. All are welcome to attend and worship together.</p>
                    <button class="w-full bg-[#205781] text-white py-2 px-4 rounded-lg hover:bg-[#4F959D] transition-colors">
                        Register Now
                    </button>
                </div>
            </div>

            <!-- Sample Event 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#205781] text-white rounded-lg p-3 text-center mr-4">
                            <div class="text-sm font-semibold">JAN</div>
                            <div class="text-xl font-bold">17</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Bible Study</h3>
                            <p class="text-sm text-gray-500">7:00 PM - 8:30 PM</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">Weekly Bible study session. We'll be studying the Book of John this month.</p>
                    <button class="w-full bg-[#205781] text-white py-2 px-4 rounded-lg hover:bg-[#4F959D] transition-colors">
                        Register Now
                    </button>
                </div>
            </div>

            <!-- Sample Event 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#205781] text-white rounded-lg p-3 text-center mr-4">
                            <div class="text-sm font-semibold">JAN</div>
                            <div class="text-xl font-bold">19</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Youth Fellowship</h3>
                            <p class="text-sm text-gray-500">6:00 PM - 8:00 PM</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">Youth fellowship gathering with games, worship, and Bible study.</p>
                    <button class="w-full bg-[#205781] text-white py-2 px-4 rounded-lg hover:bg-[#4F959D] transition-colors">
                        Register Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Events Grid -->
    <div class="events-grid hidden" id="completed-events">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Sample Completed Event -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-gray-400 text-white rounded-lg p-3 text-center mr-4">
                            <div class="text-sm font-semibold">DEC</div>
                            <div class="text-xl font-bold">25</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Christmas Service</h3>
                            <p class="text-sm text-gray-500">9:00 AM - 11:00 AM</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">Christmas Day service with special performances and message.</p>
                    <button class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                        Event Completed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const eventGrids = document.querySelectorAll('.events-grid');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons and add to clicked button
            tabBtns.forEach(b => {
                b.classList.remove('active', 'border-[#205781]', 'text-[#205781]');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            btn.classList.add('active', 'border-[#205781]', 'text-[#205781]');
            btn.classList.remove('border-transparent', 'text-gray-500');

            // Hide all grids and show the selected one
            eventGrids.forEach(grid => grid.classList.add('hidden'));
            document.getElementById(`${btn.dataset.tab}-events`).classList.remove('hidden');
        });
    });
});
</script>
@endsection
