<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login</title>
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/gkc-logo.png') }}" alt="Logo">
        <h2>Login</h2>
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group password-toggle">
                <label for="password">Password</label>
                <div class="input-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-icon" onclick="togglePassword()">
                <i id="password-icon" class="fas fa-eye"></i>
                </span>
            </div>
            </div>
            <a href="/forgot-password">Forgot password?</a>
            <button type="submit" class="btn">Continue</button>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="/signup">Sign up</a>
        </div>
    </div>
        <script>
            function togglePassword() {
                const passwordField = document.getElementById('password');
                const passwordIcon = document.getElementById('password-icon');
                const isPassword = passwordField.type === 'password';

                passwordField.type = isPassword ? 'text' : 'password';

                passwordIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            }
    </script>
</body>
</html>