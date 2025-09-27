<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GKC FaithLink')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    @stack('styles')

    <!-- Real-time Notification Styles -->
    <style>
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            min-width: 300px;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .notification-toast.show {
            transform: translateX(0);
        }

        .notification-toast.hide {
            transform: translateX(100%);
        }

        .notification-toast-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #007bff;
            display: flex;
            align-items: flex-start;
            padding: 16px;
            gap: 12px;
        }

        .notification-toast-content.notification-success {
            border-left-color: #28a745;
        }

        .notification-toast-content.notification-warning {
            border-left-color: #ffc107;
        }

        .notification-toast-content.notification-info {
            border-left-color: #17a2b8;
        }

        .notification-toast-content.notification-primary {
            border-left-color: #007bff;
        }

        .notification-toast-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-toast-icon i {
            font-size: 20px;
            color: #007bff;
        }

        .notification-success .notification-toast-icon i {
            color: #28a745;
        }

        .notification-warning .notification-toast-icon i {
            color: #ffc107;
        }

        .notification-info .notification-toast-icon i {
            color: #17a2b8;
        }

        .notification-toast-body {
            flex: 1;
            min-width: 0;
        }

        .notification-toast-title {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .notification-toast-message {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-toast-time {
            font-size: 11px;
            color: #999;
        }

        .notification-toast-actions {
            flex-shrink: 0;
            display: flex;
            gap: 4px;
        }

        .notification-toast-view,
        .notification-toast-close {
            background: none;
            border: none;
            padding: 4px;
            border-radius: 4px;
            cursor: pointer;
            color: #666;
            transition: all 0.2s;
        }

        .notification-toast-view:hover {
            background: #f8f9fa;
            color: #007bff;
        }

        .notification-toast-close:hover {
            background: #f8f9fa;
            color: #dc3545;
        }

        /* Stack multiple toasts */
        .notification-toast:nth-child(n+2) {
            top: calc(20px + (80px * var(--toast-index, 1)));
        }
    </style>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @auth
        <script src="{{ asset('js/realtime-notifications-new.js') }}" defer></script>
    @endauth
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>


