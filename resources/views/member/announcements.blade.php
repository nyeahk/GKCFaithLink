@extends('layouts.member')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Announcement</h1>
                <span class="text-sm text-gray-500 font-semibold">Latest Update</span>
            </div>
            <button class="ml-4 text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>

        <div class="flex items-center space-x-4 mb-6">
            <div class="flex items-center bg-[#F3F4F6] rounded-lg px-3 py-2 w-full max-w-md">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Search announcements" class="bg-transparent outline-none w-full text-gray-700 placeholder-gray-400" />
            </div>
            <button class="bg-[#F3F4F6] px-4 py-2 rounded-lg text-gray-700 font-medium">All categories</button>
        </div>

        <!-- Announcements List -->
        <div class="space-y-6">
            <!-- Pinned Announcement -->
            <div class="bg-blue-100 rounded-xl p-6 shadow flex flex-col border border-blue-200 relative">
                <div class="flex items-center mb-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800 mr-2">
                        <i class="fas fa-thumbtack mr-1"></i>Pinned
                    </span>
                    <span class="text-xs text-gray-500 ml-auto">2 h ago</span>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-2">Easter Sunday Service Schedule Changes</h2>
                <p class="text-gray-700 mb-4">Join us for our special Easter Sunday services with updated times to accommodate all our community members...</p>
                <button class="bg-yellow-100 text-gray-800 px-4 py-2 rounded-lg font-medium text-sm hover:bg-yellow-200 transition self-start">Read more</button>
            </div>
            <!-- Regular Announcement -->
            <div class="bg-green-100 rounded-xl p-6 shadow flex flex-col border border-green-200 relative">
                <div class="flex items-center mb-2">
                    <span class="text-xs text-gray-500 ml-auto">2 h ago</span>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-2">Easter Sunday Service Schedule Changes</h2>
                <p class="text-gray-700 mb-4">Join us for our special Easter Sunday services with updated times to accommodate all our community members...</p>
                <button class="bg-yellow-100 text-gray-800 px-4 py-2 rounded-lg font-medium text-sm hover:bg-yellow-200 transition self-start">Read more</button>
            </div>
        </div>
    </main>
</div>
@endsection
