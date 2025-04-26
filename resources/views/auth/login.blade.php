@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Login</h1>
            <p>Welcome back! Please enter your credentials.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember Me</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--background-light);
    padding: 2rem;
}

.auth-box {
    background: var(--white);
    border-radius: 8px;
    box-shadow: 0 2px 4px var(--shadow);
    padding: 2rem;
    width: 100%;
    max-width: 400px;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    color: var(--primary-dark);
    margin: 0 0 0.5rem;
}

.auth-header p {
    color: var(--text-light);
    margin: 0;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    color: var(--text-dark);
    font-weight: 500;
}

.form-input {
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 1rem;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px var(--primary-light);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
}

.form-actions {
    margin-top: 1rem;
}

.btn-primary {
    width: 100%;
    padding: 0.75rem;
    background: var(--primary);
    color: var(--white);
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.auth-footer {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.auth-footer a {
    color: var(--primary);
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: var(--danger-light);
    color: var(--danger);
    border: 1px solid var(--danger);
}
</style>
@endpush
@endsection 