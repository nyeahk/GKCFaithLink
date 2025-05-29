@extends('layouts.gkc')

@section('title', 'Forbidden')

@section('content')
<div class="error-container">
    <div class="error-content">
        <h1>403 - Forbidden</h1>
        <p>{{ $message ?? 'You do not have permission to access this page.' }}</p>
        
        <div class="debug-info">
            <h3>Debug Information</h3>
            <p>User: {{ Auth::check() ? Auth::user()->name : 'Not logged in' }}</p>
            <p>Role: {{ Auth::check() ? Auth::user()->role : 'N/A' }}</p>
            <p>URL: {{ request()->url() }}</p>
            <p>Route: {{ request()->route() ? request()->route()->getName() : 'Unknown' }}</p>
        </div>
        
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
            
            @if(Auth::check())
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Go to Admin Dashboard</a>
                @elseif(Auth::user()->role == 'staff')
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Go to Staff Dashboard</a>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection