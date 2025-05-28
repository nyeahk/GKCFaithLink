@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('title', 'Login - GKC FaithLink')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Left Side - Logo and Branding -->
        <div class="auth-logo-section">
            <div class="floating-dots"></div>
            <div class="logo-content">
                <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo">
                <h1>GKC FaithLink</h1>
                <p class="auth-subtitle">Welcome back to your faith community</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-form-section">
            <div class="auth-header">
                <h2>Sign In</h2>
                <p>Please enter your credentials to continue</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="Enter your email">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        SIGN IN
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="{{ route('register') }}" class="auth-link">Create one here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

