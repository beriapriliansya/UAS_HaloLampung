<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Add your custom admin CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('css/admin.css') }}"> --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Include default vite build if needed --}}
</head>
<body class="font-sans antialiased">
    <div class="min-vh-100 bg-light">
        @include('layouts.admin_navigation') {{-- Include admin navigation --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="container py-4">
            {{ $slot }} {{-- Content will be injected here --}}
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    @stack('scripts')
</body>
</html>