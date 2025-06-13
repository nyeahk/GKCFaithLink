@extends('layouts.app')

@section('title', 'Register - GKC FaithLink')

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
                    @error('username')
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-10 bg-white p-10 rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Create Church Account</h2>
            <p class="mt-3 text-sm text-gray-600">Register your church account to get started</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-5">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.register.post') }}" id="registrationForm" class="mt-10 space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 text-left mb-2">Username</label>
                    <input type="text" id="username" name="username" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 @error('username') border-red-300 @enderror" 
                           value="{{ old('username') }}" placeholder="Choose a username" required
                           pattern="^[a-zA-Z0-9][a-zA-Z0-9._]{1,18}[a-zA-Z0-9]$"
                           title="Username must be 3-20 characters long, start and end with a letter or number, and can only contain letters, numbers, underscores, and periods."
                           minlength="3" maxlength="20">
                    <p class="mt-1 text-xs text-gray-500">Username must be 3-20 characters, start and end with a letter or number, and can only contain letters, numbers, underscores, and periods.</p>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 text-left mb-2">Email Address</label>
                    <input type="email" id="email" name="email" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 @error('email') border-red-300 @enderror" 
                           value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 text-left mb-2">Church Position</label>
                    <select id="position" name="position" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 @error('position') border-red-300 @enderror" 
                            required>
                        <option value="" disabled {{ old('position') == '' ? 'selected' : '' }}>Select your position</option>
                        <option value="Pastor" {{ old('position') == 'Pastor' ? 'selected' : '' }}>Admin</option>
                        <option value="Staff" {{ old('position') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="Treasurer" {{ old('position') == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                        <option value="Member" {{ old('position') == 'Member' ? 'selected' : '' }}>Member</option>
                    </select>
                    @error('position')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 text-left mb-2">Password</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 @error('password') border-red-300 @enderror" 
                           placeholder="Enter password" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 text-left mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 @error('password_confirmation') border-red-300 @enderror" 
                           placeholder="Re-enter password" required>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="password_confirmation_error" class="mt-2 text-sm text-red-600 hidden">Passwords do not match.</p>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" id="submitBtn" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign Up
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('auth.login') }}" class="font-medium text-blue-600 hover:text-blue-500">Log In</a>
            </p>
        </form>
    </div>

    <script>
        // Enable the submit button when the form is valid
        document.getElementById('registrationForm').addEventListener('input', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = !this.checkValidity();
            
            // Check if passwords match
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const passwordConfirmationError = document.getElementById('password_confirmation_error');
            
            if (password.value && passwordConfirmation.value && password.value !== passwordConfirmation.value) {
                passwordConfirmationError.classList.remove('hidden');
            } else {
                passwordConfirmationError.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
