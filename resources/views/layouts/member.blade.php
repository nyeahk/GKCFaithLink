<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GKC FaithLink')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="navigation">
        <div class="container">
            <header class="top-nav">
                <div class="logo">
                    <img src="{{ asset('images/gkc-logo.png') }}" alt="GKC FaithLink Logo">
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
                <span class="name">Member</span>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a></li>
                    <li><a href="{{ route('member.events') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar"></i> Events
                    </a></li>
                    <li><a href="{{ route('member.donations') }}" class="{{ request()->routeIs('donations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-hand-holding-dollar"></i> Donation
                    </a></li>
                    <li><a href="{{ route('member.announcements') }}" class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-bullhorn"></i> Announcements
                    </a></li>
                    <li><a href="{{ route('member.profile') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
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
