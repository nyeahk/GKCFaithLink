<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h3>Quick Links</h3>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-section">
            <h4>Announcements</h4>
            <ul>
                <li>
                    <a href="{{ route('announcements.create') }}" class="{{ request()->routeIs('announcements.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i> Create New
                    </a>
                </li>
                <li>
                    <a href="{{ route('announcements.index') }}" class="{{ request()->routeIs('announcements.index') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> All Announcements
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <h4>Events</h4>
            <ul>
                <li>
                    <a href="{{ route('admin.events.create') }}" class="{{ request()->routeIs('admin.events.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i> Create Event
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> All Events
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <h4>Reports</h4>
            <ul>
                <li>
                    <a href="{{ route('reports.weekly') }}" class="{{ request()->routeIs('reports.weekly') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Weekly Report
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.monthly') }}" class="{{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Monthly Report
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="system-info">
            <p>System Status: <span class="status online">Online</span></p>
            <p>Last Updated: {{ now()->format('M d, Y H:i') }}</p>
        </div>
    </div>
</aside> 