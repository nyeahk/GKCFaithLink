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
            <!-- Fixed Sidebar (visible on lg screens and up) -->
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0 sidebar-column">
                <nav id="sidebar" class="sidebar sidebar-enhanced p-3">
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

            <!-- Scrollable Main Content Area -->
            <div class="col-12 col-lg-9 col-xl-10 p-4 main-content-column">
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
        /* Global color override for member panel */
        :root {
            /* Previous color variables remain unchanged */
            --primary: #4F959D;
            --primary-dark: #3d7a80;
            --primary-light: #98D2C0;
            --background-light: #F6F8D5;
            --white: #ffffff;
            --text-dark: #333333;
            --text-darker: #111111;
            --text-light: #666666;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --secondary: #6c757d;
            --navbar-bg: #4F959D;
        }
        
        /* Override Bootstrap's primary color throughout the member panel */
        .bg-primary {
            background-color: #4F959D !important;
        }
        
        .btn-primary {
            background-color: #4F959D !important;
            border-color: #4F959D !important;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }
        
        .btn-outline-primary {
            color: #4F959D !important;
            border-color: #4F959D !important;
        }
        
        .btn-outline-primary:hover {
            background-color: #4F959D !important;
            color: var(--white) !important;
        }
        
        .text-primary {
            color: #4F959D !important;
        }
        
        .border-primary {
            border-color: #4F959D !important;
        }
        
        /* Override any blue backgrounds in cards, alerts, badges, etc. */
        .card-header.bg-primary, 
        .card-header.bg-info, 
        .card-header.bg-blue,
        .alert-primary,
        .badge-primary {
            background-color: #4F959D !important;
            border-color: #4F959D !important;
        }
        
        /* Override any blue progress bars */
        .progress-bar.bg-primary {
            background-color: #4F959D !important;
        }
        
        /* Override any blue links */
        a.text-primary:hover, 
        a.text-primary:focus {
            color: var(--primary-dark) !important;
        }
        
        /* Override any blue borders */
        .border-primary {
            border-color: #4F959D !important;
        }
        
        /* Override any blue focus states */
        .form-control:focus {
            border-color: #4F959D !important;
            box-shadow: 0 0 0 0.2rem rgba(79, 149, 157, 0.25) !important;
        }
        
        /* Enhanced Member Dashboard Styling */
        :root {
            /* Updated to match notification page colors */
            --primary: #4F959D;
            --primary-dark: #205781;
            --primary-light: #98D2C0;
            --background-light: #F6F8D5;
            --white: #ffffff;
            --text-dark: #333333;
            --text-darker: #111111;
            --text-light: #666666;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --secondary: #6c757d;
            --border-light: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Updated navbar color to #367588 as requested */
            --navbar-bg: #367588;
            
            /* Sidebar and hover colors */
            --sidebar-bg: #4F959D;
            --sidebar-hover: #4F959D;
            --sidebar-active: #3d7a80;
            --hover-shadow: 0 4px 15px rgba(79, 149, 157, 0.4);
        }
        
        /* Update the navbar background color */
        .navbar {
            background-color: var(--navbar-bg) !important;
            height: 64px;
        }
        
        /* Ensure ALL navbar text is white, including any welcome messages */
        .navbar,
        .navbar *,
        .navbar-brand, 
        .navbar-brand strong,
        .navbar .nav-link,
        .navbar .dropdown-toggle,
        .navbar-text,
        .navbar span,
        .navbar div,
        .navbar p,
        .navbar .dropdown-item,
        .navbar .welcome-text,
        .navbar .user-greeting {
            color: var(--white) !important;
        }
        
        /* Target specific welcome message classes that might be used */
        .welcome-message,
        .user-welcome,
        .greeting-text,
        .user-name,
        .navbar .text-dark {
            color: var(--white) !important;
        }
        
        /* Ensure dropdown menus maintain proper text color */
        .navbar .dropdown-menu {
            background-color: var(--navbar-bg);
        }
        
        .navbar .dropdown-menu .dropdown-item {
            color: var(--white) !important;
        }
        
        .navbar .dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Update navbar button hover states */
        .navbar .btn:hover,
        .navbar .nav-link:hover,
        .navbar .dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: var(--white) !important;
        }
        
        /* Override Bootstrap's navbar-light class if it's being used */
        .navbar.navbar-light {
            background-color: var(--navbar-bg) !important;
        }
        
        .navbar.navbar-light .navbar-brand,
        .navbar.navbar-light .nav-link {
            color: var(--white) !important;
        }
        
        /* Update sidebar positioning to remove gap */
        .sidebar {
            background: var(--sidebar-bg);
            margin-top: 0 !important;
            border-top: none;
            border-radius: 0;
        }
        
        /* Adjust the sidebar's top position to align with navbar */
        #sidebar {
            min-height: calc(100vh - 64px) !important; 
            position: sticky !important;
            top: 64px !important; /* Match navbar height exactly */
            padding-top: 1rem !important;
        }
        
        /* Adjust the main container to align with the top of the sidebar */
        .main-container {
            margin-top: 64px;
        }
        
        /* Change text color to white */
        .sidebar-title {
            color: var(--white);
        }
        
        .sidebar .nav-link {
            color: var(--white);
        }
        
        .sidebar-icon {
            color: var(--white);
        }
        
        .sidebar-text {
            color: var(--white);
        }
        
        /* Updated hover styles with green theme */
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--white);
            transform: translateX(3px);
            box-shadow: var(--hover-shadow);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active);
            color: var(--white);
            font-weight: 600;
            box-shadow: var(--hover-shadow);
        }
        
        /* Update offcanvas sidebar for mobile as well */
        .offcanvas-enhanced {
            background: var(--sidebar-bg);
        }
        
        .offcanvas-header-enhanced {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .offcanvas-body-enhanced .nav-link {
            color: var(--white);
        }
        
        /* Updated hover styles for offcanvas */
        .offcanvas-body-enhanced .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--white);
            transform: translateX(3px);
            box-shadow: var(--hover-shadow);
        }
        
        .offcanvas-body-enhanced .nav-link.active {
            background-color: var(--sidebar-active);
            color: var(--white);
            font-weight: 600;
            box-shadow: var(--hover-shadow);
        }
        
        /* Update card styling */
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            background-color: var(--background-light);
            border-bottom: 1px solid var(--border-light);
        }
        
        /* Update button styling */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        /* Override any Bootstrap or global hover styles that might be causing purple */
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active,
        .offcanvas-body-enhanced .nav-link:hover,
        .offcanvas-body-enhanced .nav-link.active {
            background-color: var(--sidebar-hover) !important;
            color: var(--white) !important;
            transform: translateX(5px);
            box-shadow: var(--hover-shadow);
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 8px;
            /* Add a subtle border for distinction since hover color matches sidebar */
            border-left: 4px solid var(--white);
        }
        
        /* Active state with slightly darker shade */
        .sidebar .nav-link.active,
        .offcanvas-body-enhanced .nav-link.active {
            background-color: var(--sidebar-active) !important;
            font-weight: 700;
        }
        
        /* Ensure no purple gradient is applied from global styles */
        .sidebar a:hover,
        .sidebar button:hover,
        .sidebar .nav-menu a:hover,
        .sidebar .nav-menu a.active,
        .sidebar .menu-section a:hover,
        .sidebar .menu-section a.active,
        .sidebar .dropdown-menu a:hover,
        .sidebar .dropdown-menu button:hover,
        .sidebar .btn:hover,
        .sidebar .btn-submit:hover,
        .sidebar .btn-cancel:hover,
        .sidebar .nav-icons a:hover,
        .sidebar .nav-brand a:hover,
        .sidebar .user-button:hover,
        .sidebar .close:hover,
        .sidebar .submit-button:hover,
        .sidebar .qr-button:hover {
            background-color: var(--sidebar-hover) !important;
            color: var(--white) !important;
        }
        
        /* Specifically target sidebar links with the new teal color */
        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: var(--sidebar-hover) !important;
            color: var(--white) !important;
            border-left: 4px solid var(--white);
        }
        
        /* Standardize sidebar link boxes */
        .sidebar .nav-item {
            margin-bottom: 10px !important; /* Consistent spacing between items */
        }
        
        .sidebar .nav-link,
        .offcanvas-body-enhanced .nav-link {
            padding: 12px 15px !important; /* Equal padding for all links */
            border-radius: 8px !important; /* Consistent rounded corners */
            display: flex !important;
            align-items: center !important;
            height: 48px !important; /* Fixed height for all links */
            transition: all 0.3s ease !important;
        }
        
        /* Make icons consistent size and spacing */
        .sidebar-icon {
            font-size: 18px !important; /* Consistent icon size */
            width: 24px !important; /* Fixed width for icon container */
            margin-right: 12px !important; /* Consistent spacing after icon */
            text-align: center !important;
        }
        
        /* Make text consistent */
        .sidebar-text {
            font-size: 14px !important; /* Consistent text size */
            font-weight: 500 !important; /* Medium weight by default */
            flex: 1 !important; /* Take up remaining space */
        }
        
        /* Active state styling */
        .sidebar .nav-link.active,
        .offcanvas-body-enhanced .nav-link.active {
            font-weight: 600 !important; /* Slightly bolder when active */
            border-left: 4px solid var(--white) !important;
            padding-left: 11px !important; /* Adjust padding to account for border */
        }
        
        /* Apply the same styles to both desktop and mobile sidebars */
        #sidebar .nav-link,
        #sidebarOffcanvas .nav-link {
            height: 48px !important;
            padding: 12px 15px !important;
        }
    </style>
</body>
</html>






















