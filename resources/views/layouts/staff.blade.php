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
    <style>
        :root {
            --navbar-height: 56px;
            --sidebar-width: 250px;
        }
        .navbar {
            height: var(--navbar-height);
            min-height: var(--navbar-height);
            z-index: 1040;
        }
        #sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: #5483B3;
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
            background: #052659 !important;
            color: #fff !important;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
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
    @include('layouts.navigation') <!-- Top navbar -->

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header mb-4">
            <h4 class="text-white"><i class="bi bi-speedometer2"></i> Staff Panel</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('staff.dashboard') }}">
                    <i class="bi bi-house-door me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('staff.events.index') }}">
                    <i class="bi bi-calendar-event me-2"></i> Events
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('staff.registrations.index') }}">
                    <i class="bi bi-person-check me-2"></i> Registrations
                </a>
            </li>
             <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('staff.announcements.index') }}">
                    <i class="bi bi-megaphone me-2"></i> Announcements
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('staff.profile.index') }}">
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
</body>
</html>
