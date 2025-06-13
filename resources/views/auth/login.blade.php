@extends('layouts.app')

@section('title', 'Login - GKC FaithLink')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(32, 87, 129, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            min-height: 600px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
        }

        /* Left Side - Logo Section */
        .auth-logo-section {
            background: linear-gradient(135deg, #205781 0%, #4f959d 100%);
            color: #ffffff;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .floating-dots {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 40% 60%, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 100px 100px, 150px 150px, 80px 80px;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-20px) rotate(360deg); }
        }

        .logo-content {
            position: relative;
            z-index: 2;
        }

        .logo-content img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            margin-bottom: 1.5rem;
            object-fit: cover;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .logo-content img:hover {
            transform: scale(1.05);
        }

        .logo-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .auth-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            font-weight: 400;
        }

        /* Right Side - Form Section */
        .auth-form-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            color: #205781;
            font-size: 2rem;
            font-weight: 600;
            margin: 0 0 0.5rem;
        }

        .auth-header p {
            color: #4f959d;
            margin: 0;
            font-size: 1rem;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            color: #4f959d;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #4f959d;
            width: 16px;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            color: #205781;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #4f959d;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(79, 149, 157, 0.1);
        }

        .form-input::placeholder {
            color: #a0a0a0;
        }

        .form-input.is-invalid {
            border-color: #dc2626;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            color: #205781;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #205781 0%, #4f959d 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(32, 87, 129, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(32, 87, 129, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .auth-footer p {
            color: #205781;
            margin: 0;
        }

        .auth-link {
            color: #4f959d;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: #205781;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-card {
                grid-template-columns: 1fr;
                max-width: 500px;
            }

            .auth-logo-section {
                padding: 2rem;
                min-height: 300px;
            }

            .logo-content img {
                width: 80px;
                height: 80px;
            }

            .logo-content h1 {
                font-size: 2rem;
            }

            .auth-form-section {
                padding: 2rem;
            }

            .auth-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1rem;
            }

            .auth-logo-section {
                padding: 1.5rem;
                min-height: 250px;
            }

            .logo-content img {
                width: 60px;
                height: 60px;
            }

            .logo-content h1 {
                font-size: 1.5rem;
            }

            .auth-form-section {
                padding: 1.5rem;
            }

            .form-input {
                padding: 0.875rem;
            }
        }
    </style>
@endsection

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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('images/gkc-logo.png') }}" alt="Logo" class="mx-auto h-20 mb-4 rounded-full">
            <h2 class="text-2xl font-bold text-gray-800">Member Login</h2>
            <p class="text-gray-600 mt-2">Welcome back! Please login to your account.</p>
        </div>
        
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
        @endif
        
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p class="font-medium">Please correct the following errors:</p>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('auth.login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required
                           value="{{ old('email') }}"
                           class="pl-10 w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                @if($errors->has('email'))
                <p class="text-sm text-red-600 mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>
            
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required
                           class="pl-10 w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="toggle-password" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            
                        </button>
                    </div>
                </div>
                @if($errors->has('password'))
                <p class="text-sm text-red-600 mt-1">{{ $errors->first('password') }}</p>
                @endif
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                <a href="/forgot-password" class="text-sm font-medium text-blue-600 hover:text-blue-800">Forgot password?</a>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Sign In
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-600">
            Don't have an account? <a href="/register" class="font-medium text-blue-600 hover:text-blue-800">Sign up</a>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordField.type === 'password';
                
                passwordField.type = isPassword ? 'text' : 'password';
                passwordIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            });
        });
    </script>
</body>
</html>