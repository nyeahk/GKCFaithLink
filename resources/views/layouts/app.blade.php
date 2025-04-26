<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="app-container">
        <!-- Fixed Header with Navigation -->
        <header class="fixed-header">
            @include('layouts.navigation')
        </header>

        <!-- Fixed Sidebar -->
        <aside class="fixed-sidebar">
            @include('layouts.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <style>
        .app-container {
            display: flex;
            min-height: 100vh;
            background-color: #f7fafc;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .fixed-sidebar {
            position: fixed;
            top: 64px; /* Height of the header */
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: #1a365d;
            color: white;
            z-index: 999;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 64px; /* Height of the header */
            padding: 2rem;
            flex: 1;
            min-height: calc(100vh - 64px);
            background-color: #f7fafc;
        }

        @media (max-width: 768px) {
            .fixed-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .fixed-sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                margin-top: 64px;
                padding: 1rem;
            }
        }
    </style>
</body>
</html> 