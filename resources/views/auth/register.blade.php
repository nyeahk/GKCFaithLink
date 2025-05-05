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
