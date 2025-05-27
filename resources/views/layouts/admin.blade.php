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

    @include('layouts.navigation')
    <!-- Sidebar -->
    <div class="d-flex">
        <nav id="sidebar" class="sidebar bg-light p-3" style="min-width: 250px; height: 100vh;">
            <div class="sidebar-header mb-4">
                <h4 class="text-primary"><i class="bi bi-speedometer2"></i> Admin Panel</h4>

            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-people me-2"></i> List of Members
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-person-badge me-2"></i> Assign Roles
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-person-x me-2"></i> Disable Account
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-bar-chart-line me-2"></i> Reports
                    </a>
                </li>
            </ul>
        </nav>
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>