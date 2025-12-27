<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CloudBox') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-100">
                <a href="/" class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    <span class="text-xl font-bold tracking-tight">CloudBox</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Personal</p>
                
                <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard') && !request()->filled('category')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    All Files
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('dashboard', ['category' => 'photo'])" :active="request('category') === 'photo'">
                    <svg class="w-5 h-5 mr-3 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Photos
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('dashboard', ['category' => 'video'])" :active="request('category') === 'video'">
                    <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Videos
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('dashboard', ['category' => 'document'])" :active="request('category') === 'document'">
                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Documents
                </x-nav-link-sidebar>

                <x-nav-link-sidebar :href="route('dashboard', ['category' => 'audio'])" :active="request('category') === 'audio'">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"></path></svg>
                    Musics
                </x-nav-link-sidebar>

                @if(auth()->user()->is_admin)
                    <div class="pt-6">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Administrator</p>
                        
                        <x-nav-link-sidebar :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Overview
                        </x-nav-link-sidebar>

                        <x-nav-link-sidebar :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Manage Users
                        </x-nav-link-sidebar>
                    </div>
                @endif
            </nav>

            <div class="p-6 border-t border-gray-100">
            @php
                $user = auth()->user();
                $percent = ($user->used_capacity / $user->total_capacity) * 100;
                // Format GB agar lebih manusiawi
                $usedGb = number_format($user->used_capacity / (1024**3), 2);
                $totalGb = number_format($user->total_capacity / (1024**3), 0);
            @endphp

            <div class="flex justify-between items-center mb-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Storage</span>
                <span class="text-[10px] font-bold text-blue-600">{{ round($percent, 1) }}%</span>
            </div>
            
            <div class="w-full bg-gray-100 rounded-full h-2 mb-2 overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
            </div>
            
            <p class="text-[10px] text-gray-500 leading-tight">
                Using {{ $usedGb }} GB of {{ $totalGb }} GB
            </p>

            @if($percent > 90)
                <p class="text-[9px] text-red-500 mt-1 font-bold animate-pulse italic">Kapasitas hampir penuh!</p>
            @endif
        </div>

            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-bold text-gray-700 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 truncate">Settings & Profile</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="w-full text-left px-2 py-1 text-xs text-red-500 hover:underline">Logout</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto relative">
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-30 px-6 py-4 flex justify-between items-center md:hidden">
                <span class="font-bold">CloudBox</span>
                </header>

            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>