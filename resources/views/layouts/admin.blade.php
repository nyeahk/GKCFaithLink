<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GKC FaithLink')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="navigation">
        <div class="container">
            <header class="top-nav">
                <div class="logo">
                    <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo">
                    <span>GKC FaithLink</span>
                </div>
                
                <div class="nav-icons">
                    <a href="#" onclick="showNotification()"><i class="fa-solid fa-bell icon"></i></a>
                    <a href="#" onclick="logout()"><i class="fa-solid fa-arrow-right-from-bracket icon"></i></a>
                </div>
            </header>
        </div>
    </div>

    <div class="main-container">
        <aside class="sidebar">
            <div class="profile">
                <img src="{{ asset('images/P.png') }}" alt="Profile Picture">
                <span class="name">Admin Name</span>
            </div>

            <nav class="nav-menu">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a></li>
                    <li><a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar"></i> Events
                    </a></li>
                    <li><a href="{{ route('admin.donations.index') }}" class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hand-holding-dollar"></i> Donation
                    </a></li>
                    <li><a href="{{ route('announcements.index') }}" class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-bullhorn"></i> Announcements
                    </a></li>
                    <li>
                        <a href="#" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-invoice"></i> Reports
                            <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown">
                            <li><a href="{{ route('reports.weekly') }}" class="{{ request()->routeIs('reports.weekly') ? 'active' : '' }}">Weekly Report</a></li>
                            <li><a href="{{ route('reports.monthly') }}" class="{{ request()->routeIs('reports.monthly') ? 'active' : '' }}">Monthly Report</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Members
                    </a></li>
                    <li><a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showNotification() {
            // Add notification logic here
        }

        function logout() {
            // Add logout logic here
            document.getElementById('logout-form').submit();
        }
    </script>
    @stack('scripts')
</body>
</html> 