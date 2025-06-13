<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- Include navigation at the top -->
    @include('layouts.navigation')

    <div class="container-fluid">
        <div class="row">
            <!-- Desktop Sidebar (visible on lg screens and up) -->
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <nav id="sidebar" class="sidebar sidebar-enhanced p-3" style="min-height: calc(100vh - 84px); position: sticky; top: 84px;">
                    <div class="sidebar-header mb-4">
                        <h4 class="sidebar-title"><i class="bi bi-speedometer2 me-2"></i>Member Panel</h4>
                    </div>
                    <ul class="nav flex-column sidebar-nav">
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.dashboard') ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                                <i class="bi bi-house-door me-3 sidebar-icon"></i>
                                <span class="sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.events*') ? 'active' : '' }}" href="{{ route('member.events') }}">
                                <i class="bi bi-calendar-event me-3 sidebar-icon"></i>
                                <span class="sidebar-text">Events</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.donations*') ? 'active' : '' }}" href="{{ route('member.donations.index') }}">
                                <i class="bi bi-cash-coin me-3 sidebar-icon"></i>
                                <span class="sidebar-text">My Donations</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.announcements*') ? 'active' : '' }}" href="{{ route('member.announcements') }}">
                                <i class="bi bi-megaphone me-3 sidebar-icon"></i>
                                <span class="sidebar-text">Announcements</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.profile*') ? 'active' : '' }}" href="{{ route('member.profile.index') }}">
                                <i class="bi bi-people me-3 sidebar-icon"></i>
                                <span class="sidebar-text">My Profile</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="col-12 col-lg-9 col-xl-10 p-4">
                <!-- Mobile sidebar toggle button -->
                <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                    <i class="bi bi-list"></i> Menu
                </button>
                
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Offcanvas Sidebar for mobile -->
    <div class="offcanvas offcanvas-start offcanvas-enhanced d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header offcanvas-header-enhanced">
            <h5 class="offcanvas-title sidebar-title" id="sidebarOffcanvasLabel"><i class="bi bi-speedometer2 me-2"></i> Member Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body offcanvas-body-enhanced p-0">
            <ul class="nav flex-column sidebar-nav p-3">
                <li class="nav-item mb-2">
                    <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.dashboard') ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                        <i class="bi bi-house-door me-3 sidebar-icon"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.events*') ? 'active' : '' }}" href="{{ route('member.events') }}">
                        <i class="bi bi-calendar-event me-3 sidebar-icon"></i>
                        <span class="sidebar-text">Events</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.donations*') ? 'active' : '' }}" href="{{ route('member.donations.index') }}">
                        <i class="bi bi-cash-coin me-3 sidebar-icon"></i>
                        <span class="sidebar-text">My Donations</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.announcements*') ? 'active' : '' }}" href="{{ route('member.announcements') }}">
                        <i class="bi bi-megaphone me-3 sidebar-icon"></i>
                        <span class="sidebar-text">Announcements</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link sidebar-link d-flex align-items-center {{ request()->routeIs('member.profile*') ? 'active' : '' }}" href="{{ route('member.profile.index') }}">
                        <i class="bi bi-people me-3 sidebar-icon"></i>
                        <span class="sidebar-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <style>
        /* Enhanced Member Dashboard Styling */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --info-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --sidebar-bg: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: rgba(255, 255, 255, 0.2);
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.8);
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.15);
            --shadow-heavy: 0 8px 30px rgba(0, 0, 0, 0.2);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced Sidebar Styling */
        .sidebar-enhanced {
            background: var(--sidebar-bg);
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            box-shadow: var(--shadow-medium);
            border: none;
            overflow: hidden;
        }

        .sidebar-title {
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
        }

        .sidebar-nav {
            padding: 0;
        }

        .sidebar-link {
            color: var(--text-muted);
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius);
            margin: 0.25rem 0;
            transition: var(--transition);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar-link:hover::before {
            left: 100%;
        }

        .sidebar-link:hover {
            color: var(--text-light);
            background: var(--sidebar-hover);
            transform: translateX(5px);
            box-shadow: var(--shadow-light);
        }

        .sidebar-link.active {
            color: var(--text-light);
            background: var(--sidebar-active);
            box-shadow: var(--shadow-light);
            font-weight: 600;
        }

        .sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--text-light);
            border-radius: 2px 0 0 2px;
        }

        .sidebar-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar-link:hover .sidebar-icon {
            transform: scale(1.1);
        }

        .sidebar-text {
            transition: var(--transition);
        }

        /* Mobile Offcanvas Enhanced Styling */
        .offcanvas-enhanced {
            background: var(--sidebar-bg);
            border: none;
        }

        .offcanvas-header-enhanced {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .offcanvas-body-enhanced {
            background: transparent;
        }

        /* Enhanced Card Styling */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .card-header {
            background: var(--primary-gradient) !important;
            color: var(--text-light) !important;
            border: none;
            font-weight: 600;
            padding: 1.25rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Enhanced Button Styling */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            background: var(--primary-gradient);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 25px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        /* Mobile Menu Button Enhancement */
        .btn.d-lg-none {
            background: var(--primary-gradient);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
        }

        .btn.d-lg-none:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        /* Calendar Enhancements */
        .calendar-container {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .calendar-header {
            background: var(--primary-gradient);
            color: var(--text-light);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .calendar-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-nav-btn {
            background: rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .calendar-nav-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: var(--text-light);
            transform: translateY(-2px);
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            border: none;
        }

        .calendar-table td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #f1f3f4;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            height: 80px;
            vertical-align: top;
        }

        .calendar-table td:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--text-light);
            transform: scale(1.02);
        }

        .calendar-table td.today {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: var(--text-light);
            font-weight: 700;
        }

        .calendar-table td.has-events {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: var(--text-light);
        }

        .day-number {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .event-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* List Group Enhancements */
        .list-group-item {
            border: none;
            border-radius: var(--border-radius);
            margin-bottom: 0.5rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }

        .list-group-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-medium);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .calendar-header {
                flex-direction: column;
                text-align: center;
            }

            .calendar-nav {
                justify-content: center;
            }

            .calendar-table td {
                padding: 0.5rem;
                height: 60px;
            }
        }

        /* Animation for page load */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .sidebar-link {
            animation: fadeInUp 0.4s ease-out;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-enhanced::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-enhanced::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-enhanced::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-enhanced::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</body>
</html>
