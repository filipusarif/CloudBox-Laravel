<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CloudBox') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center">
                <svg class="w-16 h-16 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                <span class="text-3xl font-black tracking-tighter">CloudBox</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl shadow-blue-100 rounded-[2.5rem] border border-gray-100">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">&copy; 2025 CloudBox Secure Storage</p>
        </div>
    </div>
</body>
</html>