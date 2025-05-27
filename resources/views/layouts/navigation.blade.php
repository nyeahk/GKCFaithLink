<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fas fa-church text-primary me-2"></i>
            <strong>GKC FaithLink</strong>
        </a>

        <!-- Right side: Logout -->
        <div class="d-flex align-items-center ms-auto">
            @auth
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
