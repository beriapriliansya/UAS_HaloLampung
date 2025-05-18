<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Login</title>

    <!-- Fonts (Opsional, bisa ganti dengan font Bootstrap atau custom) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Khusus untuk halaman login admin (Opsional) --}}
    <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang netral */
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px; /* Lebar maksimum card login */
            padding: 2rem;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
            color: #0d6efd; /* Warna primary Bootstrap */
        }
        /* Sesuaikan warna dan tampilan lain jika perlu */
    </style>

    <!-- Scripts Vite (jika masih ada aset lain yang perlu, tapi untuk Bootstrap saja tidak perlu) -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased">
    <div class="login-container">
        <div class="login-card bg-white">
            <div class="login-logo">
                {{-- Ganti dengan logo gambar jika ada, atau teks --}}
                {{-- <img src="{{ asset('images/admin-logo.png') }}" alt="Admin Logo" width="150"> --}}
                {{ config('app.name', 'Laravel') }} Admin
            </div>

            {{ $slot }} {{-- Tempat konten form login akan masuk --}}

            {{-- Copyright (Opsional) --}}
            <p class="mt-4 mb-0 text-center text-muted">
                <small>© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</small>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS (hanya jika diperlukan oleh elemen di slot, biasanya tidak untuk form login sederhana) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>
</html>