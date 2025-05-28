<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
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
                <nav id="sidebar" class="sidebar bg-light p-3" style="min-height: calc(100vh - 84px); position: sticky; top: 84px;">
                    <div class="sidebar-header mb-4">
                        <h4 class="text-primary"><i class="bi bi-speedometer2"></i> Admin Panel</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i> List of Users
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center collapsed" href="#reportsSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-bar-chart-line me-2"></i> Reports
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse" id="reportsSubmenu">
                                <ul class="nav flex-column ms-3 mt-2">
                                    <li class="nav-item mb-2">
                                        <a class="nav-link d-flex align-items-center" href="{{ route('admin.reports.weekly') }}">
                                            <i class="bi bi-calendar-week me-2"></i> Weekly Report
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link d-flex align-items-center" href="{{ route('admin.reports.monthly') }}">
                                            <i class="bi bi-calendar-month me-2"></i> Monthly Report
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('admin.profile.index') }}">
                                <i class="bi bi-people me-2"></i> My Profile
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
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-primary" id="sidebarOffcanvasLabel"><i class="bi bi-speedometer2"></i> Admin Panel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav flex-column p-3">
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i> List of Users
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#reportsSubmenuMobile" data-bs-toggle="collapse" aria-expanded="false">
                        <i class="bi bi-bar-chart-line me-2"></i> Reports
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="reportsSubmenuMobile">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.reports.weekly') }}">
                                    <i class="bi bi-calendar-week me-2"></i> Weekly Report
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.reports.monthly') }}">
                                    <i class="bi bi-calendar-month me-2"></i> Monthly Report
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.profile.index') }}">
                        <i class="bi bi-people me-2"></i> My Profile
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>