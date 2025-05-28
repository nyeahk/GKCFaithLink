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
                <div class="dropdown me-3">
                    <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @php
                            try {
                                $unreadCount = auth()->user()->unreadNotifications->count();
                            } catch (\Exception $e) {
                                $unreadCount = 0;
                            }
                        @endphp
                        
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                Notifications
                                @if($unreadCount > 0)
                                    <a href="{{ route('notifications.mark-all-read') }}" class="text-decoration-none small">Mark all as read</a>
                                @endif
                            </h6>
                        </li>
                        
                        @php
                            try {
                                $recentNotifications = auth()->user()->notifications()->take(5)->get();
                            } catch (\Exception $e) {
                                $recentNotifications = collect();
                            }
                        @endphp
                        
                        @forelse($recentNotifications as $notification)
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}" 
                                   href="{{ route('notifications.show', $notification->id) }}">
                                    <div class="flex-shrink-0 me-2">
                                        @if($notification->type == 'App\Notifications\DonationApprovedNotification')
                                            <i class="fas fa-donate text-success"></i>
                                        @elseif($notification->type == 'App\Notifications\DonationStatusNotification')
                                            <i class="fas fa-check-circle text-primary"></i>
                                        @elseif($notification->type == 'App\Notifications\NewDonationNotification')
                                            <i class="fas fa-hand-holding-usd text-warning"></i>
                                        @else
                                            <i class="fas fa-bell text-secondary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small">{{ Str::limit($notification->data['message'] ?? 'New notification', 50) }}</div>
                                        <div class="text-muted smaller">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-center py-3">No notifications</span></li>
                        @endforelse
                        
                        @if($recentNotifications->count() > 0)
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">View all notifications</a></li>
                        @endif
                    </ul>
                </div>
                
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


