
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
    <style>
        :root {
            --navbar-height: 56px;
            --sidebar-width: 250px;
            --navbar-bg: #052659;
            --sidebar-bg: #5483B3;
            --card-header-bg: #7DA0CA;
            --button-bg: #7DA0CA;
            --system-bg: #C1E8FF;
            --header-text: #fff;
            --h4-color: #fff;
        }
        body {
            background: var(--system-bg) !important;
        }
        .navbar {
            height: var(--navbar-height);
            min-height: var(--navbar-height);
            z-index: 1040;
            background: var(--navbar-bg) !important;
        }
        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .navbar-text {
            color: var(--header-text) !important;
        }
        #sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1030;
            overflow: hidden;
            padding: 1.5rem 1rem 1rem 1rem;
        }
        #sidebar .nav-link,
        #sidebar .sidebar-header,
        #sidebar .bi,
        #sidebar .fa {
            color: #fff !important;
        }
        #sidebar .nav-link.active,
        #sidebar .nav-link:focus,
        #sidebar .nav-link:hover {
            background: var(--navbar-bg) !important;
            color: #fff !important;
        }
        .sidebar-header h4 {
            color: var(--h4-color) !important;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
        }
        .card-header,
        .card-header h1, .card-header h2, .card-header h3, .card-header h4, .card-header h5, .card-header h6 {
            background: var(--card-header-bg) !important;
            color: var(--header-text) !important;
        }
        .btn-primary, .btn-primary:focus, .btn-primary:active, .btn-primary:hover {
            background: var(--button-bg) !important;
            border-color: var(--button-bg) !important;
            color: #fff !important;
            box-shadow: none !important;
        }
        .btn-primary:disabled, .btn-primary.disabled {
            background: #b3cbe6 !important;
            border-color: #b3cbe6 !important;
            color: #fff !important;
        }
        @media (max-width: 991.98px) {
            #sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 1rem;
            }
            .main-content {
                margin-left: 0;
                padding-top: var(--navbar-height);
            }
        }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header mb-4">
            <h4><i class="bi bi-speedometer2"></i> Treasurer Panel</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
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
                <a class="nav-link d-flex align-items-center" href="{{ route('treasurer.profile.index') }}">
                    <i class="bi bi-people me-2"></i> My Profile
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if reports submenu should be expanded
            if (window.location.href.includes('/reports')) {
                if (document.getElementById('reportsSubmenu')) {
                    document.getElementById('reportsSubmenu').classList.add('show');
                }
            }
        });
    </script>
    @endpush
</body>
</html>