<!-- Navigation Layout -->
<div class="navigation-container">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <div class="nav-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo">
                    <img src="{{ asset('images/gkc.png') }}" alt="Logo">
                    <span>GKC FaithLink</span>
                </div>
            </div>

            <div class="nav-right">
                @auth
                    <div class="user-menu">
                        <span class="user-name">{{ auth()->user()->name ?? 'User' }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="auth-links">
                        <a href="{{ route('login') }}" class="auth-link">Login</a>
                        <a href="{{ route('register') }}" class="auth-link">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>GKC FaithLink</span>
            </a>
        </div>

        @auth
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('announcements.index') }}" class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i>
                            <span>Announcements</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar"></i>
                            <span>Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.donations.index') }}" class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                            <i class="fas fa-donate"></i>
                            <span>Donations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Members</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.weekly') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="profile-info">
                        <span class="user-name">{{ auth()->user()->name ?? 'User' }}</span>
                        <span class="user-role">{{ auth()->user()->role ?? 'Member' }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>

    <!-- Main Content Wrapper -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

<style>
.navigation-container {
    display: flex;
    min-height: 100vh;
}

/* Top Navigation Styles */
.top-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 64px;
    background-color: white;
    border-bottom: 1px solid #e2e8f0;
    z-index: 1000;
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 1.5rem;
    max-width: 100%;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #4a5568;
    cursor: pointer;
    padding: 0.5rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: inherit;
}

.logo img {
    height: 32px;
    width: auto;
}

.logo span {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a365d;
}

.nav-right {
    display: flex;
    align-items: center;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-name {
    color: #4a5568;
    font-weight: 500;
}

.logout-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    color: #4a5568;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.logout-button:hover {
    background-color: #f7fafc;
}

.auth-links {
    display: flex;
    gap: 1rem;
}

.auth-link {
    color: #4a5568;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.auth-link:hover {
    background-color: #f7fafc;
}

/* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 64px;
    left: 0;
    bottom: 0;
    width: 250px;
    background-color: white;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
}

.sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 0;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin-bottom: 0.5rem;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #4a5568;
    text-decoration: none;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.sidebar-nav a:hover {
    background-color: #f7fafc;
}

.sidebar-nav a.active {
    background-color: #ebf8ff;
    color: #3182ce;
}

.sidebar-nav i {
    width: 1.25rem;
    text-align: center;
}

.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid #e2e8f0;
}

.user-profile {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-info {
    display: flex;
    flex-direction: column;
}

.user-role {
    font-size: 0.875rem;
    color: #718096;
}

/* Main Content Styles */
.main-content {
    flex: 1;
    margin-left: 250px;
    margin-top: 64px;
    padding: 2rem;
    background-color: #f7fafc;
    min-height: calc(100vh - 64px);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .logo span {
        display: none;
    }

    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
});
</script> 