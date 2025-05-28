@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('title', 'Register - GKC FaithLink')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Left Side - Logo and Branding -->
        <div class="auth-logo-section">
            <div class="floating-dots"></div>
            <div class="logo-content">
                <img src="{{ asset('images/gkc logo new.jpeg') }}" alt="GKC FaithLink Logo">
                <h1>GKC FaithLink</h1>
                <p class="auth-subtitle">Join our faith community today</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-form-section">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Please fill in your information to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input id="username" type="text" class="form-input @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus placeholder="Enter your username">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter your email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Create a password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirm Password
                    </label>
                    <input id="password-confirm" type="password" class="form-input" name="password_confirmation" autocomplete="new-password" placeholder="Confirm your password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        CREATE ACCOUNT
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection