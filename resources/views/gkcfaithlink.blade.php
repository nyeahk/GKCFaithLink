<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GKC FaithLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="md:flex">
            <!-- Left side - Image -->
            <div class="md:w-1/2 bg-[#1DA1F2] p-8 flex items-center justify-center">
                <img src="{{ asset('images/gkc-logo.png') }}" alt="GKC FaithLink Logo" class="w-48 h-auto rounded-[95px]">
            </div>
            
            <!-- Right side - Content -->
            <div class="md:w-1/2 p-8 md:p-12">
                <div class="text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Welcome to GKC FaithLink</h1>
                    <p class="text-gray-600 mb-8">Your gateway to faith, events, and community.</p>
                    
                    <div class="space-y-4">
                        <a href="{{ route('auth.register') }}" 
                           class="block w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors text-center">
                            Create Account
                        </a>
                        <a href="{{ route('auth.login') }}" 
                           class="block w-full bg-white text-blue-600 py-3 px-6 rounded-lg font-medium border-2 border-blue-600 hover:bg-blue-50 transition-colors text-center">
                            Sign In
                        </a>
                    </div>
                    
                    <div class="mt-8 text-sm text-gray-500">
                        <p>Join our community of faith and stay connected with:</p>
                        <ul class="mt-2 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                Upcoming Events
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-hand-holding-heart text-blue-600 mr-2"></i>
                                Donation Opportunities
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                                Important Announcements
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>