<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Treasurer Dashboard')</title>
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
                <nav id="sidebar" class="sidebar treasurer-sidebar-enhanced p-3" style="min-height: calc(100vh - 84px); position: sticky; top: 84px;">
                    <div class="sidebar-header mb-4">
                        <h4 class="treasurer-sidebar-title"><i class="bi bi-cash-stack me-2"></i>Treasurer Panel</h4>
                    </div>
                    <ul class="nav flex-column treasurer-sidebar-nav">
                        <li class="nav-item mb-2">
                            <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.dashboard') ? 'active' : '' }}" href="{{ route('treasurer.dashboard') }}">
                                <i class="bi bi-house-door me-3 treasurer-sidebar-icon"></i>
                                <span class="treasurer-sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.donations.index') ? 'active' : '' }}" href="{{ route('treasurer.donations.index') }}">
                                <i class="bi bi-cash-coin me-3 treasurer-sidebar-icon"></i>
                                <span class="treasurer-sidebar-text">View Donations</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.donations.create') ? 'active' : '' }}" href="{{ route('treasurer.donations.create') }}">
                                <i class="bi bi-plus-circle me-3 treasurer-sidebar-icon"></i>
                                <span class="treasurer-sidebar-text">Add Manual Donation</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
<<<<<<< HEAD
                            <a class="nav-link treasurer-sidebar-link treasurer-sidebar-dropdown d-flex align-items-center {{ request()->routeIs('treasurer.reports*') ? 'active' : '' }}" href="#reportsSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('treasurer.reports*') ? 'true' : 'false' }}">
                                <i class="bi bi-bar-chart-line me-3 treasurer-sidebar-icon"></i>
                                <span class="treasurer-sidebar-text">Reports</span>
                                <i class="bi bi-chevron-down ms-auto treasurer-dropdown-icon"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('treasurer.reports*') ? 'show' : '' }}" id="reportsSubmenu">
                                <ul class="nav flex-column treasurer-submenu ms-3 mt-2">
                                    <li class="nav-item mb-2">
                                        <a class="nav-link treasurer-sidebar-link treasurer-submenu-link d-flex align-items-center {{ request()->routeIs('treasurer.reports.weekly') ? 'active' : '' }}" href="{{ route('treasurer.reports.weekly') }}">
                                            <i class="bi bi-calendar-week me-3 treasurer-sidebar-icon"></i>
                                            <span class="treasurer-sidebar-text">Weekly Report</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link treasurer-sidebar-link treasurer-submenu-link d-flex align-items-center {{ request()->routeIs('treasurer.reports.monthly') ? 'active' : '' }}" href="{{ route('treasurer.reports.monthly') }}">
                                            <i class="bi bi-calendar-month me-3 treasurer-sidebar-icon"></i>
                                            <span class="treasurer-sidebar-text">Monthly Report</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
=======
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
>>>>>>> 70a61f8a1b2736bf77305a50653004188f6627e9
                        <li class="nav-item mb-2">
                            <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.profile*') ? 'active' : '' }}" href="{{ route('treasurer.profile.index') }}">
                                <i class="bi bi-person-circle me-3 treasurer-sidebar-icon"></i>
                                <span class="treasurer-sidebar-text">My Profile</span>
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
    <div class="offcanvas offcanvas-start treasurer-offcanvas-enhanced d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header treasurer-offcanvas-header-enhanced">
            <h5 class="offcanvas-title treasurer-sidebar-title" id="sidebarOffcanvasLabel"><i class="bi bi-cash-stack me-2"></i> Treasurer Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body treasurer-offcanvas-body-enhanced p-0">
            <ul class="nav flex-column treasurer-sidebar-nav p-3">
                <li class="nav-item mb-2">
<<<<<<< HEAD
                    <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.dashboard') ? 'active' : '' }}" href="{{ route('treasurer.dashboard') }}">
                        <i class="bi bi-house-door me-3 treasurer-sidebar-icon"></i>
                        <span class="treasurer-sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.donations.index') ? 'active' : '' }}" href="{{ route('treasurer.donations.index') }}">
                        <i class="bi bi-cash-coin me-3 treasurer-sidebar-icon"></i>
                        <span class="treasurer-sidebar-text">View Donations</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.donations.create') ? 'active' : '' }}" href="{{ route('treasurer.donations.create') }}">
                        <i class="bi bi-plus-circle me-3 treasurer-sidebar-icon"></i>
                        <span class="treasurer-sidebar-text">Add Manual Donation</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link treasurer-sidebar-link treasurer-sidebar-dropdown d-flex align-items-center {{ request()->routeIs('treasurer.reports*') ? 'active' : '' }}" href="#reportsSubmenuMobile" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('treasurer.reports*') ? 'true' : 'false' }}">
                        <i class="bi bi-bar-chart-line me-3 treasurer-sidebar-icon"></i>
                        <span class="treasurer-sidebar-text">Reports</span>
                        <i class="bi bi-chevron-down ms-auto treasurer-dropdown-icon"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('treasurer.reports*') ? 'show' : '' }}" id="reportsSubmenuMobile">
                        <ul class="nav flex-column treasurer-submenu ms-3 mt-2">
                            <li class="nav-item mb-2">
                                <a class="nav-link treasurer-sidebar-link treasurer-submenu-link d-flex align-items-center {{ request()->routeIs('treasurer.reports.weekly') ? 'active' : '' }}" href="{{ route('treasurer.reports.weekly') }}">
                                    <i class="bi bi-calendar-week me-3 treasurer-sidebar-icon"></i>
                                    <span class="treasurer-sidebar-text">Weekly Report</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link treasurer-sidebar-link treasurer-submenu-link d-flex align-items-center {{ request()->routeIs('treasurer.reports.monthly') ? 'active' : '' }}" href="{{ route('treasurer.reports.monthly') }}">
                                    <i class="bi bi-calendar-month me-3 treasurer-sidebar-icon"></i>
                                    <span class="treasurer-sidebar-text">Monthly Report</span>
=======
                            <a class="nav-link d-flex align-items-center" href="{{ route('treasurer.dashboard') }}">
                                <i class="bi bi-house-door me-2"></i> Dashboard
                            </a>
                        </li>
                <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('treasurer.donations.index') }}">
                                <i class="bi bi-cash-coin me-2"></i> View Donations
                            </a>
                        </li>
                <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('treasurer.donations.create') }}">
                                <i class="bi bi-plus-circle me-2"></i> Add Manual Donation
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
>>>>>>> 70a61f8a1b2736bf77305a50653004188f6627e9
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link treasurer-sidebar-link d-flex align-items-center {{ request()->routeIs('treasurer.profile*') ? 'active' : '' }}" href="{{ route('treasurer.profile.index') }}">
                        <i class="bi bi-person-circle me-3 treasurer-sidebar-icon"></i>
                        <span class="treasurer-sidebar-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
<<<<<<< HEAD

    <style>
        /* Enhanced Treasurer Dashboard Styling */
        :root {
            --treasurer-primary-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --treasurer-secondary-gradient: linear-gradient(135deg, #0f7173 0%, #2d8f47 100%);
            --treasurer-accent-gradient: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            --treasurer-success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --treasurer-warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            --treasurer-info-gradient: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
            --treasurer-sidebar-bg: linear-gradient(180deg, #11998e 0%, #38ef7d 100%);
            --treasurer-sidebar-hover: rgba(255, 255, 255, 0.12);
            --treasurer-sidebar-active: rgba(255, 255, 255, 0.2);
            --treasurer-text-light: #ffffff;
            --treasurer-text-muted: rgba(255, 255, 255, 0.85);
            --treasurer-shadow-light: 0 2px 10px rgba(17, 153, 142, 0.2);
            --treasurer-shadow-medium: 0 4px 20px rgba(17, 153, 142, 0.25);
            --treasurer-shadow-heavy: 0 8px 30px rgba(17, 153, 142, 0.3);
            --treasurer-border-radius: 12px;
            --treasurer-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced Treasurer Sidebar Styling */
        .treasurer-sidebar-enhanced {
            background: var(--treasurer-sidebar-bg);
            border-radius: 0 var(--treasurer-border-radius) var(--treasurer-border-radius) 0;
            box-shadow: var(--treasurer-shadow-medium);
            border: none;
            overflow: hidden;
            position: relative;
        }

        .treasurer-sidebar-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.08) 0%, transparent 50%, rgba(255, 255, 255, 0.04) 100%);
            pointer-events: none;
        }

        .treasurer-sidebar-enhanced::after {
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

        .treasurer-sidebar-title {
            color: var(--treasurer-text-light);
            font-weight: 700;
            font-size: 1.3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            position: relative;
            z-index: 3;
        }

        .treasurer-sidebar-nav {
            padding: 0;
            position: relative;
            z-index: 3;
        }

        .treasurer-sidebar-link {
            color: var(--treasurer-text-muted);
            padding: 1rem 1.25rem;
            border-radius: var(--treasurer-border-radius);
            margin: 0.25rem 0;
            transition: var(--treasurer-transition);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .treasurer-sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.6s;
        }

        .treasurer-sidebar-link:hover::before {
            left: 100%;
        }

        .treasurer-sidebar-link:hover {
            color: var(--treasurer-text-light);
            background: var(--treasurer-sidebar-hover);
            transform: translateX(8px);
            box-shadow: var(--treasurer-shadow-light);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .treasurer-sidebar-link.active {
            color: var(--treasurer-text-light);
            background: var(--treasurer-sidebar-active);
            box-shadow: var(--treasurer-shadow-light);
            font-weight: 600;
            transform: translateX(5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .treasurer-sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(180deg, var(--treasurer-text-light) 0%, rgba(255, 255, 255, 0.8) 100%);
            border-radius: 2px 0 0 2px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
        }

        .treasurer-sidebar-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--treasurer-transition);
        }

        .treasurer-sidebar-link:hover .treasurer-sidebar-icon {
            transform: scale(1.15) rotate(-5deg);
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }

        .treasurer-sidebar-link.active .treasurer-sidebar-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
        }

        .treasurer-sidebar-text {
            transition: var(--treasurer-transition);
        }

        /* Dropdown Enhancements */
        .treasurer-sidebar-dropdown {
            position: relative;
        }

        .treasurer-dropdown-icon {
            transition: var(--treasurer-transition);
            font-size: 0.9rem;
        }

        .treasurer-sidebar-dropdown[aria-expanded="true"] .treasurer-dropdown-icon {
            transform: rotate(180deg);
        }

        .treasurer-submenu {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--treasurer-border-radius);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .treasurer-submenu-link {
            padding: 0.75rem 1rem;
            margin: 0.1rem 0;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.03);
        }

        .treasurer-submenu-link:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }

        .treasurer-submenu-link.active {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(3px);
        }

        /* Mobile Offcanvas Enhanced Styling */
        .treasurer-offcanvas-enhanced {
            background: var(--treasurer-sidebar-bg);
            border: none;
        }

        .treasurer-offcanvas-header-enhanced {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .treasurer-offcanvas-body-enhanced {
            background: transparent;
        }

        /* Enhanced Card Styling for Treasurer */
        .card {
            border: none;
            border-radius: var(--treasurer-border-radius);
            box-shadow: var(--treasurer-shadow-light);
            transition: var(--treasurer-transition);
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
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.02) 0%, rgba(56, 239, 125, 0.02) 100%);
            pointer-events: none;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--treasurer-shadow-medium);
        }

        .card-header {
            background: var(--treasurer-primary-gradient) !important;
            color: var(--treasurer-text-light) !important;
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

        /* Enhanced Button Styling for Treasurer */
        .btn-primary {
            background: var(--treasurer-primary-gradient);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--treasurer-transition);
            box-shadow: var(--treasurer-shadow-light);
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
            box-shadow: var(--treasurer-shadow-medium);
            background: var(--treasurer-primary-gradient);
        }

        .btn-outline-primary {
            border: 2px solid #11998e;
            color: #11998e;
            border-radius: 25px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: var(--treasurer-transition);
        }

        .btn-outline-primary:hover {
            background: var(--treasurer-primary-gradient);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--treasurer-shadow-light);
            color: white;
        }

        /* Mobile Menu Button Enhancement */
        .btn.d-lg-none {
            background: var(--treasurer-primary-gradient);
            border: none;
            border-radius: var(--treasurer-border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--treasurer-shadow-light);
            transition: var(--treasurer-transition);
        }

        .btn.d-lg-none:hover {
            transform: translateY(-2px);
            box-shadow: var(--treasurer-shadow-medium);
        }

        /* Stats Cards Enhancement for Treasurer Dashboard */
        .treasurer-stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: var(--treasurer-border-radius);
            padding: 2rem;
            box-shadow: var(--treasurer-shadow-light);
            transition: var(--treasurer-transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .treasurer-stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--treasurer-primary-gradient);
        }

        .treasurer-stats-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--treasurer-shadow-medium);
        }

        .treasurer-stats-card .stats-icon {
            font-size: 2.5rem;
            background: var(--treasurer-primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .treasurer-stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #11998e;
            margin: 0.5rem 0;
        }

        .treasurer-stats-card .stats-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        /* Financial Cards Enhancement */
        .treasurer-financial-card {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: var(--treasurer-border-radius);
            padding: 1.5rem;
            box-shadow: var(--treasurer-shadow-light);
            transition: var(--treasurer-transition);
            border: 1px solid rgba(17, 153, 142, 0.1);
            position: relative;
            overflow: hidden;
        }

        .treasurer-financial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--treasurer-primary-gradient);
        }

        .treasurer-financial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--treasurer-shadow-medium);
            border-color: rgba(17, 153, 142, 0.2);
        }

        .treasurer-financial-card .amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #11998e;
            margin: 0.5rem 0;
        }

        .treasurer-financial-card .currency {
            font-size: 1rem;
            color: #059669;
            font-weight: 600;
        }

        .treasurer-financial-card .label {
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        /* Donation Table Enhancement */
        .treasurer-table {
            border-radius: var(--treasurer-border-radius);
            overflow: hidden;
            box-shadow: var(--treasurer-shadow-light);
            border: none;
        }

        .treasurer-table thead {
            background: var(--treasurer-primary-gradient);
            color: white;
        }

        .treasurer-table thead th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .treasurer-table tbody tr {
            transition: var(--treasurer-transition);
        }

        .treasurer-table tbody tr:hover {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            transform: scale(1.01);
        }

        .treasurer-table tbody td {
            border-color: rgba(17, 153, 142, 0.1);
        }

        /* Filter Section Enhancement */
        .treasurer-filter-section {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: var(--treasurer-border-radius);
            padding: 1.5rem;
            box-shadow: var(--treasurer-shadow-light);
            margin-bottom: 2rem;
            border: 1px solid rgba(17, 153, 142, 0.1);
        }

        .treasurer-filter-section h5 {
            color: #11998e;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* Chart Container Enhancement */
        .treasurer-chart-container {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: var(--treasurer-border-radius);
            padding: 1.5rem;
            box-shadow: var(--treasurer-shadow-light);
            border: 1px solid rgba(17, 153, 142, 0.1);
        }

        /* Animation for page load */
        @keyframes treasurerFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .treasurer-stats-card, .treasurer-financial-card, .treasurer-filter-section, .treasurer-chart-container {
            animation: treasurerFadeInUp 0.6s ease-out;
        }

        .treasurer-sidebar-link {
            animation: treasurerFadeInUp 0.4s ease-out;
        }

        /* Custom scrollbar for treasurer sidebar */
        .treasurer-sidebar-enhanced::-webkit-scrollbar {
            width: 6px;
        }

        .treasurer-sidebar-enhanced::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .treasurer-sidebar-enhanced::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .treasurer-sidebar-enhanced::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .treasurer-stats-card, .treasurer-financial-card {
                margin-bottom: 1rem;
            }

            .treasurer-filter-section {
                padding: 1rem;
            }
        }

        /* Additional Treasurer-specific enhancements */
        .treasurer-badge {
            background: var(--treasurer-primary-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .treasurer-alert {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: none;
            border-radius: var(--treasurer-border-radius);
            color: #065f46;
            font-weight: 500;
            border-left: 4px solid #11998e;
        }

        .treasurer-progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(17, 153, 142, 0.1);
        }

        .treasurer-progress .progress-bar {
            background: var(--treasurer-primary-gradient);
            border-radius: 4px;
        }

        /* Report specific styling */
        .report-header {
            background: var(--treasurer-primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: var(--treasurer-border-radius) var(--treasurer-border-radius) 0 0;
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

        /* Donation Form Enhancement */
        .treasurer-form-container {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: var(--treasurer-border-radius);
            padding: 2rem;
            box-shadow: var(--treasurer-shadow-light);
            border: 1px solid rgba(17, 153, 142, 0.1);
        }

        .treasurer-form-container .form-label {
            color: #11998e;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .treasurer-form-container .form-control {
            border: 2px solid rgba(17, 153, 142, 0.1);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: var(--treasurer-transition);
        }

        .treasurer-form-container .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }

        /* Success/Error Messages */
        .treasurer-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            color: #065f46;
            border-radius: var(--treasurer-border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .treasurer-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            color: #991b1b;
            border-radius: var(--treasurer-border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
=======
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if reports submenu should be expanded
            if (window.location.href.includes('/reports')) {
                document.getElementById('reportsSubmenu').classList.add('show');
                
                // For mobile
                if (document.getElementById('reportsSubmenuMobile')) {
                    document.getElementById('reportsSubmenuMobile').classList.add('show');
                }
            }
        });
    </script>
    @endpush
>>>>>>> 70a61f8a1b2736bf77305a50653004188f6627e9
</body>
</html>
