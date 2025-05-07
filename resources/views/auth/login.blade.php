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