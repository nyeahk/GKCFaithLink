@extends('layouts.app')

@section('title', 'Staff Login - GKC FaithLink')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Left Side - Logo and Branding -->
        <div class="auth-logo-section">
            <div class="floating-dots"></div>
            <div class="logo-content">
                <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo">
                <h1>GKC FaithLink</h1>
                <p class="auth-subtitle">Staff Portal</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-form-section">
            <div class="auth-header">
                <h2>Staff Login</h2>
                <p>Please enter your credentials to access the staff dashboard</p>
            </div>

            <form method="POST" action="{{ route('staff.login') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" required autocomplete="current-password">
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group remember-me">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Remember Me
                    </label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </div>

                <div class="auth-links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot Your Password?
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection