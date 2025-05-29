@extends('layouts.gkc')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    <p>Welcome to the admin dashboard!</p>
                    
                    <div class="alert alert-info">
                        <p>This is a simple dashboard view that doesn't require any variables.</p>
                    </div>
                    
                    <div class="mt-4">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection