<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Dashboard')</title>
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
                <nav id="sidebar" class="sidebar staff-sidebar-enhanced p-3" style="min-height: calc(100vh - 84px); position: sticky; top: 84px;">
                    <div class="sidebar-header mb-4">
                        <h4 class="staff-sidebar-title"><i class="bi bi-speedometer2 me-2"></i>Staff Panel</h4>
                    </div>
                    <ul class="nav flex-column staff-sidebar-nav">
                        <li class="nav-item mb-2">
                            <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                                <i class="bi bi-house-door me-3 staff-sidebar-icon"></i>
                                <span class="staff-sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.events*') ? 'active' : '' }}" href="{{ route('staff.events.index') }}">
                                <i class="bi bi-calendar-event me-3 staff-sidebar-icon"></i>
                                <span class="staff-sidebar-text">Events</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.announcements*') ? 'active' : '' }}" href="{{ route('staff.announcements.index') }}">
                                <i class="bi bi-megaphone me-3 staff-sidebar-icon"></i>
                                <span class="staff-sidebar-text">Announcements</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.users*') ? 'active' : '' }}" href="{{ route('staff.users.index') }}">
                                <i class="bi bi-people me-3 staff-sidebar-icon"></i>
                                <span class="staff-sidebar-text">View Members</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.profile*') ? 'active' : '' }}" href="{{ route('staff.profile.index') }}">
                                <i class="bi bi-person-circle me-3 staff-sidebar-icon"></i>
                                <span class="staff-sidebar-text">My Profile</span>
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
    <div class="offcanvas offcanvas-start staff-offcanvas-enhanced d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header staff-offcanvas-header-enhanced">
            <h5 class="offcanvas-title staff-sidebar-title" id="sidebarOffcanvasLabel"><i class="bi bi-speedometer2 me-2"></i> Staff Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body staff-offcanvas-body-enhanced p-0">
            <ul class="nav flex-column staff-sidebar-nav p-3">
                <li class="nav-item mb-2">
                    <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                        <i class="bi bi-house-door me-3 staff-sidebar-icon"></i>
                        <span class="staff-sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.events*') ? 'active' : '' }}" href="{{ route('staff.events.index') }}">
                        <i class="bi bi-calendar-event me-3 staff-sidebar-icon"></i>
                        <span class="staff-sidebar-text">Events</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.announcements*') ? 'active' : '' }}" href="{{ route('staff.announcements.index') }}">
                        <i class="bi bi-megaphone me-3 staff-sidebar-icon"></i>
                        <span class="staff-sidebar-text">Announcements</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.users*') ? 'active' : '' }}" href="{{ route('staff.users.index') }}">
                        <i class="bi bi-people me-3 staff-sidebar-icon"></i>
                        <span class="staff-sidebar-text">View Members</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link staff-sidebar-link d-flex align-items-center {{ request()->routeIs('staff.profile*') ? 'active' : '' }}" href="{{ route('staff.profile.index') }}">
                        <i class="bi bi-person-circle me-3 staff-sidebar-icon"></i>
                        <span class="staff-sidebar-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <style>
        /* Enhanced Staff Dashboard Styling */
        :root {
            --staff-primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --staff-secondary-gradient: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%);
            --staff-success-gradient: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
            --staff-warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --staff-info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --staff-sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --staff-sidebar-hover: rgba(255, 255, 255, 0.15);
            --staff-sidebar-active: rgba(255, 255, 255, 0.25);
            --staff-text-light: #ffffff;
            --staff-text-muted: rgba(255, 255, 255, 0.85);
            --staff-shadow-light: 0 2px 10px rgba(255, 107, 107, 0.2);
            --staff-shadow-medium: 0 4px 20px rgba(255, 107, 107, 0.25);
            --staff-shadow-heavy: 0 8px 30px rgba(255, 107, 107, 0.3);
            --staff-border-radius: 12px;
            --staff-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced Staff Sidebar Styling */
        .staff-sidebar-enhanced {
            background: var(--staff-sidebar-bg);
            border-radius: 0 var(--staff-border-radius) var(--staff-border-radius) 0;
            box-shadow: var(--staff-shadow-medium);
            border: none;
            overflow: hidden;
            position: relative;
        }

        .staff-sidebar-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .staff-sidebar-title {
            color: var(--staff-text-light);
            font-weight: 700;
            font-size: 1.3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .staff-sidebar-nav {
            padding: 0;
            position: relative;
            z-index: 2;
        }

        .staff-sidebar-link {
            color: var(--staff-text-muted);
            padding: 1rem 1.25rem;
            border-radius: var(--staff-border-radius);
            margin: 0.25rem 0;
            transition: var(--staff-transition);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .staff-sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .staff-sidebar-link:hover::before {
            left: 100%;
        }

        .staff-sidebar-link:hover {
            color: var(--staff-text-light);
            background: var(--staff-sidebar-hover);
            transform: translateX(8px);
            box-shadow: var(--staff-shadow-light);
        }

        .staff-sidebar-link.active {
            color: var(--staff-text-light);
            background: var(--staff-sidebar-active);
            box-shadow: var(--staff-shadow-light);
            font-weight: 600;
            transform: translateX(5px);
        }

        .staff-sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--staff-text-light);
            border-radius: 2px 0 0 2px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .staff-sidebar-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--staff-transition);
        }

        .staff-sidebar-link:hover .staff-sidebar-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .staff-sidebar-link.active .staff-sidebar-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
        }

        .staff-sidebar-text {
            transition: var(--staff-transition);
        }

        /* Mobile Offcanvas Enhanced Styling */
        .staff-offcanvas-enhanced {
            background: var(--staff-sidebar-bg);
            border: none;
        }

        .staff-offcanvas-header-enhanced {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .staff-offcanvas-body-enhanced {
            background: transparent;
        }

        /* Enhanced Card Styling for Staff */
        .card {
            border: none;
            border-radius: var(--staff-border-radius);
            box-shadow: var(--staff-shadow-light);
            transition: var(--staff-transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--staff-shadow-medium);
        }

        .card-header {
            background: var(--staff-primary-gradient) !important;
            color: var(--staff-text-light) !important;
            border: none;
            font-weight: 600;
            padding: 1.25rem;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .card-body {
            padding: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        /* Enhanced Button Styling for Staff */
        .btn-primary {
            background: var(--staff-primary-gradient);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--staff-transition);
            box-shadow: var(--staff-shadow-light);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--staff-shadow-medium);
            background: var(--staff-primary-gradient);
        }

        .btn-outline-primary {
            border: 2px solid #ff6b6b;
            color: #ff6b6b;
            border-radius: 25px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: var(--staff-transition);
        }

        .btn-outline-primary:hover {
            background: var(--staff-primary-gradient);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--staff-shadow-light);
            color: white;
        }

        /* Mobile Menu Button Enhancement */
        .btn.d-lg-none {
            background: var(--staff-primary-gradient);
            border: none;
            border-radius: var(--staff-border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--staff-shadow-light);
            transition: var(--staff-transition);
        }

        .btn.d-lg-none:hover {
            transform: translateY(-2px);
            box-shadow: var(--staff-shadow-medium);
        }

        /* Stats Cards Enhancement */
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: var(--staff-border-radius);
            padding: 2rem;
            box-shadow: var(--staff-shadow-light);
            transition: var(--staff-transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--staff-primary-gradient);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--staff-shadow-medium);
        }

        .stats-card .stats-icon {
            font-size: 2.5rem;
            background: var(--staff-primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #ff6b6b;
            margin: 0.5rem 0;
        }

        .stats-card .stats-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        /* Calendar Enhancements for Staff - Updated to match member calendar */
        .calendar-container {
            border-radius: var(--staff-border-radius);
            overflow: hidden;
            box-shadow: var(--staff-shadow-light);
            border: 1px solid #e0e0e0;
        }

        .calendar-header {
            background: var(--staff-primary-gradient);
            color: var(--staff-text-light);
            padding: 1rem;
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
            color: var(--staff-text-light);
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-nav-btn {
            background: rgba(255, 255, 255, 0.2);
            color: var(--staff-text-light);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            font-weight: 500;
            transition: var(--staff-transition);
            backdrop-filter: blur(10px);
        }

        .calendar-nav-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: var(--staff-text-light);
            transform: translateY(-2px);
        }

        /* Calendar Table Styling - Updated to match member calendar */
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calendar-table th, 
        .calendar-table td {
            text-align: center;
            padding: 0;
            position: relative;
            border: 1px solid #e0e0e0;
            height: 80px;
            vertical-align: top;
        }

        .calendar-table th {
            background-color: #f8f9fa;
            padding: 10px 0;
            font-weight: 500;
            height: auto;
            color: #495057;
        }

        .calendar-table td {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .calendar-table td:hover {
            background-color: #f0f0f0;
        }

        .day-number {
            display: block;
            padding: 5px;
            font-weight: 500;
            text-align: right;
        }

        .other-month {
            color: #aaa;
            background-color: #f9f9f9;
        }

        .today {
            background-color: #e8f4ff;
        }

        .today .day-number {
            color: #0d6efd;
            font-weight: bold;
        }

        .has-events {
            position: relative;
            background-color: #e6fffa;
        }

        .event-indicator {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.75rem;
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-radius: 4px;
            margin: 0 5px;
            padding: 2px 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .event-indicator i {
            font-size: 0.8rem;
        }

        .event-count {
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .calendar-table td {
                height: 60px;
            }
            
            .day-number {
                font-size: 0.9rem;
            }
            
            .event-indicator {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .calendar-table td {
                height: 50px;
            }
            
            .day-number {
                font-size: 0.8rem;
                padding: 3px;
            }
            
            .event-indicator {
                margin: 0 2px;
                padding: 1px 2px;
            }
        }

        /* Animation for page load */
        @keyframes staffFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .stats-card {
            animation: staffFadeInUp 0.6s ease-out;
        }

        .staff-sidebar-link {
            animation: staffFadeInUp 0.4s ease-out;
        }

        /* Custom scrollbar for staff sidebar */
        .staff-sidebar-enhanced::-webkit-scrollbar {
            width: 6px;
        }

        .staff-sidebar-enhanced::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .staff-sidebar-enhanced::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .staff-sidebar-enhanced::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
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
        }

        /* Additional Staff-specific enhancements */
        .staff-badge {
            background: var(--staff-primary-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .staff-alert {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border: none;
            border-radius: var(--staff-border-radius);
            color: #8b4513;
            font-weight: 500;
        }

        .staff-table {
            border-radius: var(--staff-border-radius);
            overflow: hidden;
            box-shadow: var(--staff-shadow-light);
        }

        .staff-table thead {
            background: var(--staff-primary-gradient);
            color: white;
        }

        .staff-table tbody tr:hover {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            transform: scale(1.01);
            transition: var(--staff-transition);
        }
    </style>
</body>
</html>


