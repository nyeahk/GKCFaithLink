<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Registration</title>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
</head>
<body>
    <div class="container">
        <form id="registrationForm">
            <h2>Create Church Account</h2>
            <p>Register your church account to get started</p>

            <label for="username">Username</label>
            <input type="text" id="username" placeholder="Choose a username" required>
            <span id="usernameError"></span>

            <label for="email">Email Address</label>
            <input type="email" id="email" placeholder="you@example.com" required>
            <span id="emailError"></span>

            <label for="position">Church Position</label>
            <select id="position" required>
                <option value="" disabled selected>Select your position</option>
                <option value="Pastor">Pastor</option>
                <option value="Staff">Staff</option>
                <option value="Trearsurer">Trearsurer</option>
                <option value="Member">Member</option>
            </select>

            <label for="password">Password</label>
            <input type="password" id="password" placeholder="Enter password" required>
            <span id="passwordError"></span>
            <div id="strengthBar"></div>

            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" placeholder="Re-enter password" required>
            <span id="confirmPasswordError"></span>

            <div class="terms">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
            </div>

            <button type="submit" id="submitBtn" disabled>Sign Up</button>
            <p>Already have an account? <a href="#">Log In</a></p>
        </form>
    </div>
    <script src="validation.js"></script>
</body>
</html>
