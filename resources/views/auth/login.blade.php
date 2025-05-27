<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="login-card mx-auto">
        <div class="text-center mb-3">
            <img src="{{ asset('images/gkc-logo.jpeg') }}" alt="Logo" class="login-logo rounded-circle">
        </div>
        <h3 class="text-center fw-bold mb-1">Welcome Back</h3>
        <p class="text-center text-muted mb-4" style="font-size:1.05rem;">Please login to continue</p>
        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label mb-1">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}">
                </div>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password" class="form-label mb-1">Password</label>
                <div class="input-group" id="show_hide_password">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                </div>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember" style="font-size:0.97rem;">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">Sign In</button>
        </form>
        <div class="text-center mt-3" style="font-size:0.97rem;">
            Don't have an account? <a href="/register" class="text-primary fw-semibold text-decoration-none">Sign up</a>
        </div>
    </div>
</body>
</html>
