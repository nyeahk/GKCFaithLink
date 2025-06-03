<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ Auth::check() ? route(Auth::user()->getRoleDashboardRoute()) : route('login') }}">
            <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo" class="logo me-2" style="width: 40px; height: 40px; border-radius: 50%;">
            <strong>GKC FaithLink</strong>
        </a>

        <!-- Right side: Notifications and Logout -->
        <div class="d-flex align-items-center ms-auto">
            @auth
                <!-- Notifications Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="navbarDropdownNotifications">
                        <li class="dropdown-header bg-light">
                            <div class="d-flex justify-content-between align-items-center px-2 py-2">
                                <span class="fw-bold">Notifications</span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.mark-all-read') }}" class="text-decoration-none small" 
                                       onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();">
                                        Mark all as read
                                    </a>
                                    <form id="mark-all-read-form" action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </li>
                        
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2 {{ $notification->read_at ? '' : 'unread-notification' }}" 
                                   href="{{ route('notifications.show', $notification->id) }}">
                                    <div class="flex-shrink-0 me-2">
                                        @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                            <div class="notification-icon bg-success-light">
                                                <i class="fas fa-donate text-success"></i>
                                            </div>
                                        @elseif($notification->type == 'App\Notifications\DonationDeclinedNotification')
                                            <div class="notification-icon bg-danger-light">
                                                <i class="fas fa-times-circle text-danger"></i>
                                            </div>
                                        @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                            @if(isset($notification->data['status']) && $notification->data['status'] == 'declined')
                                                <div class="notification-icon bg-danger-light">
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                </div>
                                            @else
                                                <div class="notification-icon bg-primary-light">
                                                    <i class="fas fa-check-circle text-primary"></i>
                                                </div>
                                            @endif
                                        @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                            <div class="notification-icon bg-warning-light">
                                                <i class="fas fa-hand-holding-usd text-warning"></i>
                                            </div>
                                        @else
                                            <div class="notification-icon bg-secondary-light">
                                                <i class="fas fa-bell text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-truncate">{{ $notification->data['message'] ?? 'New notification' }}</div>
                                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li><div class="dropdown-item text-center py-3 text-muted">No notifications</div></li>
                        @endforelse
                        
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                View all notifications
                            </a>
                        </li>
                    </ul>
                </li>
                
                <span class="me-3 text-muted">Hi, <strong>{{ Auth::user()->username }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>





