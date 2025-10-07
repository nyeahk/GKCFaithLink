<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification-overrides.css') }}">
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
                <nav id="sidebar" class="sidebar admin-sidebar-enhanced p-3" style="min-height: calc(100vh - 64px); position: fixed; top: 64px; width: inherit; max-width: inherit;">
                    <div class="sidebar-header mb-4">
                        <h4 class="admin-sidebar-title"><i class="bi bi-speedometer2 me-2"></i> Admin Panel</h4>
                    </div>
                    <ul class="nav flex-column admin-sidebar-nav">
                        <li class="nav-item mb-2">
                            <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door me-3 admin-sidebar-icon"></i>
                                <span class="admin-sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-3 admin-sidebar-icon"></i>
                                <span class="admin-sidebar-text">List of Users</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" 
                               href="#reportsSubmenu" 
                               data-bs-toggle="collapse" 
                               aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}">
                                <i class="bi bi-bar-chart-line me-2"></i> Reports
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportsSubmenu">
                                <ul class="nav flex-column ms-3 mt-2">
                                    <li class="nav-item mb-2">
                                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('reports.weekly') ? 'active' : '' }}" 
                                           href="{{ route('reports.weekly') }}">
                                            <i class="bi bi-calendar-week me-2"></i> Weekly Report
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('reports.monthly') ? 'active' : '' }}" 
                                           href="{{ route('reports.monthly') }}">
                                            <i class="bi bi-calendar-month me-2"></i> Monthly Report
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                                <i class="bi bi-person-circle me-3 admin-sidebar-icon"></i>
                                <span class="admin-sidebar-text">My Profile</span>
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
    <div class="offcanvas offcanvas-start admin-offcanvas-enhanced d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header admin-offcanvas-header-enhanced">
            <h5 class="offcanvas-title admin-sidebar-title" id="sidebarOffcanvasLabel"><i class="bi bi-speedometer2 me-2"></i> Admin Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body admin-offcanvas-body-enhanced p-0">
            <ul class="nav flex-column admin-sidebar-nav p-3">
                <li class="nav-item mb-2">
                    <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door me-3 admin-sidebar-icon"></i>
                        <span class="admin-sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-3 admin-sidebar-icon"></i>
                        <span class="admin-sidebar-text">List of Users</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" 
                       href="#reportsSubmenuMobile" 
                       data-bs-toggle="collapse" 
                       aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}">
                        <i class="bi bi-bar-chart-line me-2"></i> Reports
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportsSubmenuMobile">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('reports.weekly') ? 'active' : '' }}" 
                                   href="{{ route('reports.weekly') }}">
                                    <i class="bi bi-calendar-week me-2"></i> Weekly Report
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('reports.monthly') ? 'active' : '' }}" 
                                   href="{{ route('reports.monthly') }}">
                                    <i class="bi bi-calendar-month me-2"></i> Monthly Report
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link admin-sidebar-link d-flex align-items-center {{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                        <i class="bi bi-person-circle me-3 admin-sidebar-icon"></i>
                        <span class="admin-sidebar-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <style>
        /* Update admin color palette to perfectly match member panel */
        :root {
            --admin-primary: #4F959D;            /* Teal - Main brand color */
            --admin-primary-dark: #3d7a80;       /* Darker teal - For hover states */
            --admin-primary-light: #98D2C0;      /* Light teal/mint - For secondary elements */
            --admin-background-light: #F6F8D5;   /* Very light yellow/green - For backgrounds */
            --admin-navbar-bg: #367588;          /* Blue-teal - For navbar background */
            --admin-sidebar-bg: #4F959D;
            --admin-sidebar-hover: rgba(255, 255, 255, 0.15);
            --admin-sidebar-active: #3d7a80;
            --admin-text-light: #ffffff;
            --admin-text-dark: #333333;
            --admin-text-darker: #111111;
            --admin-text-muted: rgba(255, 255, 255, 0.85);
            --admin-shadow-light: 0 2px 10px rgba(79, 149, 157, 0.2);
            --admin-shadow-medium: 0 4px 20px rgba(79, 149, 157, 0.25);
            --admin-shadow-heavy: 0 8px 30px rgba(79, 149, 157, 0.3);
            --admin-border-radius: 12px;
            --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Admin Navbar */
        .admin-navbar,
        .admin-header,
        .navbar,
        .top-nav,
        .navigation-header {
            background-color: var(--admin-navbar-bg) !important;
            color: var(--admin-text-light) !important;
            padding: 0.5rem 2rem !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
            height: 64px !important;
            min-height: 64px !important;
            display: flex !important;
            align-items: center !important;
        }

        .navbar .container-fluid {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .navbar-brand,
        .nav-link,
        .navbar-text,
        .navbar-nav .nav-link {
            color: var(--admin-text-light) !important;
            margin-top: -0.25rem !important;
        }

        /* Navbar Links and Dropdowns */
        .navbar .nav-link,
        .navbar .dropdown-toggle,
        .navbar .dropdown-item {
            color: var(--admin-text-light) !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease !important;
        }

        .navbar .nav-link:hover,
        .navbar .dropdown-toggle:hover,
        .navbar .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--admin-text-light) !important;
        }

        /* Dropdown / notification styling is centralized in layouts/navigation.blade.php */

        /* User Info Section */
        .navbar .user-info {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        .navbar .user-greeting {
            color: var(--admin-text-light) !important;
            font-weight: 500 !important;
        }

        .navbar .user-name {
            color: var(--admin-text-light) !important;
            font-weight: 600 !important;
        }

        /* Logout Link */
        .navbar .logout-link {
            color: var(--admin-text-light) !important;
            font-weight: 500 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px !important;
            transition: all 0.3s ease !important;
        }

        .navbar .logout-link:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--admin-text-light) !important;
        }

        /* Icons in Navbar */
        .navbar .bi,
        .navbar i {
            color: var(--admin-text-light) !important;
            font-size: 1.1rem !important;
        }

        /* Mobile Menu Button */
        .navbar-toggler {
            border: none !important;
            padding: 0.5rem !important;
            color: var(--admin-text-light) !important;
        }

        .navbar-toggler:focus {
            box-shadow: none !important;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        /* Admin Sidebar */
        .admin-sidebar-enhanced {
            background: var(--admin-sidebar-bg);
            border-radius: 0 0 var(--admin-border-radius) 0;
            box-shadow: var(--admin-shadow-medium);
        }

        .admin-sidebar-link {
            color: var(--admin-text-light);
            transition: var(--admin-transition);
        }

        .admin-sidebar-link:hover {
            background-color: var(--admin-sidebar-hover);
            transform: translateX(5px);
        }

        .admin-sidebar-link.active {
            background-color: var(--admin-sidebar-active);
            font-weight: 600;
        }

        /* Admin Cards */
        .card {
            border-radius: var(--admin-border-radius);
            box-shadow: var(--admin-shadow-light);
        }

        .card-header {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%) !important;
            color: var(--admin-text-light) !important;
            border: none;
        }

        /* Admin Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--admin-transition);
            box-shadow: var(--admin-shadow-light);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #367588 0%, #4F959D 100%);
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow-medium);
            color: var(--admin-text-light);
        }

        .btn-outline-primary {
            border: 2px solid #4F959D;
            color: #4F959D;
            border-radius: 25px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: var(--admin-transition);
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-light);
            color: var(--admin-text-light);
        }

        /* Update all button hover states */
        .btn:hover,
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-success:hover,
        .btn-info:hover,
        .btn-warning:hover,
        .btn-danger:hover,
        .btn-light:hover,
        .btn-dark:hover,
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover,
        .btn-outline-warning:hover,
        .btn-outline-danger:hover,
        .btn-outline-light:hover,
        .btn-outline-dark:hover {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%) !important;
            color: var(--admin-text-light) !important;
            border-color: transparent !important;
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-medium);
        }

        /* Mobile Menu Button Enhancement */
        .btn.d-lg-none {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%);
            border: none;
            border-radius: var(--admin-border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--admin-shadow-light);
            transition: var(--admin-transition);
        }

        .btn.d-lg-none:hover {
            background: linear-gradient(135deg, #367588 0%, #4F959D 100%);
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-medium);
            color: var(--admin-text-light);
        }

        /* Action Buttons */
        .btn-action {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%);
            color: var(--admin-text-light);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: var(--admin-transition);
        }

        .btn-action:hover {
            background: linear-gradient(135deg, #367588 0%, #4F959D 100%);
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-medium);
            color: var(--admin-text-light);
        }

        /* Table Action Buttons */
        .table .btn {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%);
            color: var(--admin-text-light);
            border: none;
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
            transition: var(--admin-transition);
        }

        .table .btn:hover {
            background: linear-gradient(135deg, #367588 0%, #4F959D 100%);
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-medium);
            color: var(--admin-text-light);
        }

        /* Filter Button Enhancement */
        .filter-button {
            background: linear-gradient(135deg, #4F959D 0%, #367588 100%) !important;
            color: var(--admin-text-light) !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.25rem !important;
            border: none !important;
            box-shadow: var(--admin-shadow-light) !important;
            transition: var(--admin-transition) !important;
        }
        
        .filter-button:hover {
            background: linear-gradient(135deg, #367588 0%, #4F959D 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--admin-shadow-medium) !important;
            color: var(--admin-text-light) !important;
        }

        /* Admin Tables */
        .admin-table thead {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
            color: var(--admin-text-light);
        }

        /* Admin Stats Cards */
        .admin-stats-card::before {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
        }

        .admin-stats-card .stats-icon {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Admin Calendar */
        .calendar-header {
            background: linear-gradient(180deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
            color: var(--admin-text-light);
        }

        .calendar-table th {
            background-color: var(--admin-primary-light);
            color: var(--admin-primary-dark);
        }

        .calendar-table .today {
            background-color: var(--admin-background-light);
        }

        /* Admin Badges */
        .admin-badge {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
        }

        /* Admin Progress Bars */
        .admin-progress .progress-bar {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
        }

        /* Admin Alerts */
        .admin-alert {
            border-left: 4px solid var(--admin-primary);
        }

        /* Enhanced Admin Sidebar Styling */
        .admin-sidebar-enhanced {
            background: var(--admin-sidebar-bg);
            border-radius: 0 0 var(--admin-border-radius) 0;
            box-shadow: var(--admin-shadow-medium);
            border: none;
            overflow: hidden;
            position: relative;
        }

        .admin-sidebar-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.08) 0%, transparent 50%, rgba(255, 255, 255, 0.04) 100%);
            pointer-events: none;
        }

        .admin-sidebar-enhanced::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .admin-sidebar-title {
            color: var(--admin-text-light);
            font-weight: 700;
            font-size: 1.3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            position: relative;
            z-index: 3;
        }

        .admin-sidebar-nav {
            padding: 0;
            position: relative;
            z-index: 3;
        }

        .admin-sidebar-link {
            color: var(--admin-text-muted);
            padding: 1rem 1.25rem;
            border-radius: var(--admin-border-radius);
            margin: 0.25rem 0;
            transition: var(--admin-transition);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.6s;
        }

        .admin-sidebar-link:hover::before {
            left: 100%;
        }

        .admin-sidebar-link:hover {
            color: var(--admin-text-light);
            background: var(--admin-sidebar-hover);
            transform: translateX(8px);
            box-shadow: var(--admin-shadow-light);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .admin-sidebar-link.active {
            color: var(--admin-text-light);
            background: var(--admin-sidebar-active);
            box-shadow: var(--admin-shadow-light);
            font-weight: 600;
            transform: translateX(5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .admin-sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(180deg, var(--admin-text-light) 0%, rgba(255, 255, 255, 0.8) 100%);
            border-radius: 2px 0 0 2px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
        }

        .admin-sidebar-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--admin-transition);
        }

        .admin-sidebar-link:hover .admin-sidebar-icon {
            transform: scale(1.15) rotate(-5deg);
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }

        .admin-sidebar-link.active .admin-sidebar-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
        }

        .admin-sidebar-text {
            transition: var(--admin-transition);
        }

        /* Dropdown Enhancements */
        .admin-sidebar-dropdown {
            position: relative;
        }

        .admin-dropdown-icon {
            transition: var(--admin-transition);
            font-size: 0.9rem;
        }

        .admin-sidebar-dropdown[aria-expanded="true"] .admin-dropdown-icon {
            transform: rotate(180deg);
        }

        .admin-submenu {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--admin-border-radius);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-submenu-link {
            padding: 0.75rem 1rem;
            margin: 0.1rem 0;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.03);
        }

        .admin-submenu-link:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }

        .admin-submenu-link.active {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(3px);
        }

        /* Mobile Offcanvas Enhanced Styling */
        .admin-offcanvas-enhanced {
            background: var(--admin-sidebar-bg);
            border: none;
        }

        .admin-offcanvas-header-enhanced {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .admin-offcanvas-body-enhanced {
            background: transparent;
        }

        /* Enhanced Card Styling for Admin */
        .card {
            border: none;
            border-radius: var(--admin-border-radius);
            box-shadow: var(--admin-shadow-light);
            transition: var(--admin-transition);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
            pointer-events: none;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--admin-shadow-medium);
        }

        .card-header {
            background: var(--admin-primary-gradient) !important;
            color: var(--admin-text-light) !important;
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

        .card-header h5, .card-header h4, .card-header h3 {
            margin: 0;
            font-weight: 700;
            position: relative;
            z-index: 2;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
            z-index: 2;
        }

        /* Stats Cards Enhancement for Admin Reports */
        .admin-stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: var(--admin-border-radius);
            padding: 2rem;
            box-shadow: var(--admin-shadow-light);
            transition: var(--admin-transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .admin-stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--admin-primary-gradient);
        }

        .admin-stats-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--admin-shadow-medium);
        }

        .admin-stats-card .stats-icon {
            font-size: 2.5rem;
            background: var(--admin-primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin: 0.5rem 0;
        }

        .admin-stats-card .stats-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        /* Filter Section Enhancement */
        .admin-filter-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: var(--admin-border-radius);
            padding: 1.5rem;
            box-shadow: var(--admin-shadow-light);
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .admin-filter-section h5 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* Table Enhancements for Admin */
        .admin-table {
            border-radius: var(--admin-border-radius);
            overflow: hidden;
            box-shadow: var(--admin-shadow-light);
            border: none;
        }

        .admin-table thead {
            background: #367588 !important;
            color: var(--admin-text-light) !important;
        }

        .admin-table thead th {
            background: #367588 !important;
            color: var(--admin-text-light) !important;
            font-weight: 600 !important;
            padding: 1rem !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.9rem !important;
            letter-spacing: 0.5px !important;
        }

        .admin-table thead th:first-child,
        .admin-table thead th:last-child {
            border-radius: var(--admin-border-radius) !important;
        }

        .admin-table tbody tr {
            transition: var(--admin-transition);
        }

        .admin-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(79, 149, 157, 0.1) 0%, rgba(54, 117, 136, 0.1) 100%) !important;
        }

        .admin-table tbody td {
            padding: 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid var(--border-light) !important;
        }

        /* Chart Container Enhancement */
        .admin-chart-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: var(--admin-border-radius);
            padding: 1.5rem;
            box-shadow: var(--admin-shadow-light);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        /* Animation for page load */
        @keyframes adminFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .admin-stats-card, .admin-filter-section, .admin-chart-container {
            animation: adminFadeInUp 0.6s ease-out;
        }

        .admin-sidebar-link {
            animation: adminFadeInUp 0.4s ease-out;
        }

        /* Custom scrollbar for admin sidebar */
        .admin-sidebar-enhanced::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar-enhanced::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar-enhanced::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .admin-sidebar-enhanced::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .admin-stats-card {
                margin-bottom: 1rem;
            }

            .admin-filter-section {
                padding: 1rem;
            }
        }

        /* Additional Admin-specific enhancements */
        .admin-badge {
            background: var(--admin-primary-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-alert {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: none;
            border-radius: var(--admin-border-radius);
            color: #1565c0;
            font-weight: 500;
            border-left: 4px solid #667eea;
        }

        .admin-progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(102, 126, 234, 0.1);
        }

        .admin-progress .progress-bar {
            background: var(--admin-primary-gradient);
            border-radius: 4px;
        }

        /* Report specific styling */
        .report-header {
            background: var(--admin-primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: var(--admin-border-radius) var(--admin-border-radius) 0 0;
            position: relative;
            overflow: hidden;
        }

        .report-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .report-header h1, .report-header h2 {
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .report-date-range {
            opacity: 0.9;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Table Header Styles */
        .table thead th,
        .table th,
        .table-header th,
        .data-table thead th,
        .admin-table thead th,
        .staff-table thead th,
        .table-responsive thead th {
            background: #367588 !important;
            color: var(--admin-text-light) !important;
            font-weight: 600 !important;
            padding: 1rem !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.9rem !important;
            letter-spacing: 0.5px !important;
        }

        .table thead th:first-child,
        .table th:first-child,
        .table-header th:first-child,
        .data-table thead th:first-child,
        .admin-table thead th:first-child,
        .staff-table thead th:first-child,
        .table-responsive thead th:first-child {
            border-top-left-radius: var(--admin-border-radius) !important;
        }

        .table thead th:last-child,
        .table th:last-child,
        .table-header th:last-child,
        .data-table thead th:last-child,
        .admin-table thead th:last-child,
        .staff-table thead th:last-child,
        .table-responsive thead th:last-child {
            border-top-right-radius: var(--admin-border-radius) !important;
        }

        /* Table Container */
        .table-responsive,
        .table-container,
        .data-table-container,
        .admin-table-container {
            border-radius: var(--admin-border-radius) !important;
            box-shadow: var(--admin-shadow-light) !important;
            overflow: hidden !important;
        }

        /* Table Body Styles */
        .table tbody tr {
            transition: var(--admin-transition);
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(79, 149, 157, 0.1) 0%, rgba(54, 117, 136, 0.1) 100%) !important;
        }

        .table tbody td {
            padding: 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid var(--border-light) !important;
        }

        /* Update admin table specific styles */
        .admin-table thead {
            background: #367588 !important;
        }

        .admin-table thead th {
            background: #367588 !important;
            color: var(--admin-text-light) !important;
        }

        /* Update card headers to match */
        .card-header {
            background: #367588 !important;
            color: var(--admin-text-light) !important;
        }

        .card-header h5, 
        .card-header h4, 
        .card-header h3,
        .card-header .text-primary {
            color: var(--admin-text-light) !important;
        }

        /* Calendar and Events Archive Spacing */
        .calendar-container {
            margin-bottom: 2rem !important;
            padding-bottom: 2rem !important;
            border-bottom: 1px solid rgba(79, 149, 157, 0.2) !important;
        }

        .card.shadow-sm.mb-4 {
            margin-top: 2rem !important;
        }

        /* Ensure proper spacing in the dashboard layout */
        .container-fluid {
            padding-top: 2rem !important;
        }

        .row {
            margin-bottom: 2rem !important;
        }

        /* Add spacing between sections */
        .card + .card {
            margin-top: 2rem !important;
        }

        /* Calendar specific spacing */
        .calendar-table {
            margin-bottom: 2rem !important;
        }

        /* Past Events Archive specific spacing */
        .card-header.bg-secondary {
            margin-top: 2rem !important;
        }

        /* Ensure navbar header-level text and icons are white. Avoid broad selectors that cascade into dropdowns. */
        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .navbar-text,
        .navbar .navbar-nav .nav-link,
        .navbar .bi,
        .navbar i {
            color: var(--admin-text-light) !important;
        }

        /* Specific styles for greeting and logout */
        .navbar .welcome-text,
        .navbar .user-greeting,
        .navbar .user-name,
        .navbar .logout-link,
        .navbar .nav-link[href*="logout"] {
            color: var(--admin-text-light) !important;
            font-weight: 500;
        }

        .navbar .welcome-text:hover,
        .navbar .user-greeting:hover,
        .navbar .user-name:hover,
        .navbar .logout-link:hover,
        .navbar .nav-link[href*="logout"]:hover {
            color: var(--admin-text-light) !important;
            opacity: 0.9;
        }

        /* Additional navbar text color overrides */
        .navbar .text-dark,
        .navbar .text-muted,
        .navbar .text-secondary,
        .navbar .text-primary {
            color: var(--admin-text-light) !important;
        }

        /* Ensure icons are also white */
        .navbar .bi,
        .navbar i,
        .navbar .fas,
        .navbar .far,
        .navbar .fab {
            color: var(--admin-text-light) !important;
        }

        /* Admin Panel Layout */
        .admin-container {
            display: flex;
            min-height: calc(100vh - 64px);
            margin-top: 64px;
        }

        .admin-sidebar {
            width: 250px;
            background: var(--admin-sidebar-bg);
            position: fixed;
            top: 64px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .admin-content {
            flex: 1;
            margin-left: 250px;
            padding: 1rem;
        }

        /* Adjust container padding */
        .container-fluid {
            padding-top: 0.5rem !important;
        }

        /* Adjust card margins */
        .card {
            margin-bottom: 1rem !important;
        }

        /* Adjust row margins */
        .row {
            margin-bottom: 1rem !important;
        }

        /* Adjust breadcrumb spacing */
        .breadcrumb {
            margin-bottom: 0.5rem !important;
            padding: 0.5rem 0 !important;
        }

        /* Adjust section spacing */
        .section {
            margin-top: 0.5rem !important;
            margin-bottom: 1rem !important;
        }

        /* Adjust table spacing */
        .table-responsive {
            margin-top: 0.5rem !important;
        }

        /* Adjust form spacing */
        .form-group {
            margin-bottom: 0.75rem !important;
        }

        /* Adjust button spacing */
        .btn-group {
            margin-bottom: 0.5rem !important;
        }

        /* Adjust alert spacing */
        .alert {
            margin-bottom: 0.75rem !important;
        }
    </style>
</body>
</html>












