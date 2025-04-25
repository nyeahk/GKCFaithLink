<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GKC FaithLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gkc.css') }}">
</head>
<body>
    <div class="welcome-container">
        <div class="logo">
            <img src="{{ asset('images/gkc-logo.png') }}" alt="GKC FaithLink Logo" class="logo">
        </div>
        <h1>Welcome to GKC FaithLink</h1>
        <p>Your gateway to faith, events, and community.</p>
        <div class="auth-buttons">
            <a href="{{ route('member.member-signup') }}" class="btn btn-primary">Sign Up</a>
            <a href="{{ route('member.member-login') }}" class="btn btn-secondary">Sign In</a>
        </div>
    </div>
</body>
</html>