@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Staff Login</h1>
            <p>Access the staff portal</p>
        </div>
        
        <div class="auth-body">
            <form method="POST" action="{{ route('staff.login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="{{ route('staff.register') }}" class="auth-link">Create one here</a></p>
                </div>

                @if (Route::has('password.request'))
                <div class="forgot-password-link">
                    <a href="{{ route('password.request') }}" class="auth-link">
                        Forgot Your Password?
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--background-light) 0%, var(--secondary-mint) 100%);
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.auth-card {
    background: var(--white);
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
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent-teal) 100%);
    color: var(--white);
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
    border: 4px solid var(--white);
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
    background: var(--white);
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h2 {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 600;
    margin: 0 0 0.5rem;
}

.auth-header p {
    color: var(--accent-teal);
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
    color: var(--accent-teal);
    font-weight: 500;
    font-size: 0.95rem;
}

.form-label i {
    color: var(--accent-teal);
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
    color: var(--primary-dark);
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: var(--accent-teal);
    background-color: var(--white);
    box-shadow: 0 0 0 3px rgba(79, 149, 157, 0.1);
}

.form-input::placeholder {
    color: #a0a0a0;
}

.checkbox-group {
    margin: 1.5rem 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--accent-teal);
    cursor: pointer;
    font-size: 0.95rem;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
    border: 1px solid #d1d5db;
    border-radius: 3px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    position: relative;
    cursor: pointer;
    vertical-align: middle;
}

.checkbox-label input[type="checkbox"]:checked {
    background-color: var(--accent-teal);
    border-color: var(--accent-teal);
}

.checkbox-label input[type="checkbox"]:checked::after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
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
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent-teal) 100%);
    color: var(--white);
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
    padding-top: 1rem;
    color: #4a5568;
    font-size: 0.95rem;
}

.auth-footer p {
    margin: 0;
}

.auth-link {
    color: var(--accent-teal);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.auth-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.form-actions {
    margin-top: 1.5rem;
}

.btn-primary {
    background-color: var(--primary-dark);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.75rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-primary:hover {
    background-color: var(--accent-teal);
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.alert-error {
    background-color: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-error i {
    color: #dc2626;
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.alert-content p {
    margin: 0;
    font-size: 0.95rem;
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
@endpush
@endsection
