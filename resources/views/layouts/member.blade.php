<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GKC FaithLink')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-[#F6F8D5] min-h-screen">
    <!-- Top Navigation -->
    <nav class="bg-[#205781] shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="sidebar-toggle" class="text-white mr-4 focus:outline-none md:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <img src="{{ asset('images/gkc-logo.png') }}" alt="GKC FaithLink Logo" class="h-12 w-auto rounded-full shadow-md">
                    <span class="ml-3 text-xl font-bold text-white">GKC FaithLink</span>
                </div>
                <div class="flex items-center space-x-6">
                    <button class="text-white hover:text-[#98D2C0] focus:outline-none relative group transition-colors">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-[#4F959D] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center shadow-md">3</span>
                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 hidden group-hover:block transform transition-all duration-200 ease-in-out">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <a href="#" class="block px-4 py-3 hover:bg-[#F6F8D5] transition-colors">
                                    <p class="text-sm text-gray-700 font-medium">New event: Sunday Service</p>
                                    <span class="text-xs text-gray-500">2 hours ago</span>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-[#F6F8D5] transition-colors">
                                    <p class="text-sm text-gray-700 font-medium">Donation received</p>
                                    <span class="text-xs text-gray-500">Yesterday</span>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-[#F6F8D5] transition-colors">
                                    <p class="text-sm text-gray-700 font-medium">New announcement</p>
                                    <span class="text-xs text-gray-500">3 days ago</span>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-100">
                                <a href="#" class="text-sm text-[#205781] hover:text-[#4F959D] font-medium">View all notifications</a>
                            </div>
                        </div>
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <button onclick="logout()" class="text-white hover:text-[#98D2C0] focus:outline-none flex items-center space-x-2 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex h-screen pt-16">
        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-[#4F959D] shadow-lg fixed left-0 top-16 bottom-0 overflow-y-auto transition-all duration-300 ease-in-out z-40 transform -translate-x-full md:translate-x-0">
            <div class="flex flex-col items-center justify-center p-6 border-b border-[#4F959D]">
                <div class="flex flex-col items-center space-y-3">
                    <img src="{{ auth()->user()->image_path ? asset('storage/' . auth()->user()->image_path) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                         alt="Profile Picture" 
                         class="h-20 w-20 rounded-full ring-2 ring-white shadow-md object-cover">
                    <div class="text-center">
                        <div class="font-bold text-white text-lg">{{ auth()->user()->name ??'' }}</div>
                        <p class="text-xs text-[#98D2C0] flex items-center justify-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                            {{ auth()->user()->position ?? 'Member' }}
                        </p>
                    </div>
                </div>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('member.dashboard') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('member.dashboard') ? 'bg-[#98D2C0] text-white shadow-md' : 'text-white hover:bg-[#98D2C0] hover:shadow-md' }}">
                            <i class="fa-solid fa-gauge w-5"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.events') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('member.events') ? 'bg-[#98D2C0] text-white shadow-md' : 'text-white hover:bg-[#98D2C0] hover:shadow-md' }}">
                            <i class="fa-solid fa-calendar w-5"></i>
                            <span class="font-medium">Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.donations') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('member.donations') ? 'bg-[#98D2C0] text-white shadow-md' : 'text-white hover:bg-[#98D2C0] hover:shadow-md' }}">
                            <i class="fa-solid fa-hand-holding-dollar w-5"></i>
                            <span class="font-medium">Donation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.announcements') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('member.announcements') ? 'bg-[#98D2C0] text-white shadow-md' : 'text-white hover:bg-[#98D2C0] hover:shadow-md' }}">
                            <i class="fa-solid fa-bullhorn w-5"></i>
                            <span class="font-medium">Announcements</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.profile') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('member.profile') ? 'bg-[#98D2C0] text-white shadow-md' : 'text-white hover:bg-[#98D2C0] hover:shadow-md' }}">
                            <i class="fa-solid fa-user w-5"></i>
                            <span class="font-medium">My Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="flex-1 p-6 overflow-auto bg-[#F6F8D5] transition-all duration-300 ease-in-out md:ml-64">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function logout() {
            document.getElementById('logout-form').submit();
        }
        
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mainContent = document.getElementById('main-content');
            
            // Function to toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
                
                // Add/remove overflow hidden to body when sidebar is open
                if (!sidebar.classList.contains('-translate-x-full')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
            
            // Toggle sidebar when button is clicked
            sidebarToggle.addEventListener('click', toggleSidebar);
            
            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when a link is clicked (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) { // Only on mobile
                        toggleSidebar();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 