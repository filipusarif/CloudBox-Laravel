<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CloudBox') }} - Secure Cloud Storage</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 selection:bg-blue-600 selection:text-white">

    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                <span class="text-2xl font-extrabold tracking-tight">CloudBox</span>
            </div>

            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-bold text-gray-700 hover:text-blue-600 transition">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-700 hover:text-blue-600 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition transform hover:-translate-y-0.5">Start for Free</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="relative pt-40 pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <span class="inline-block px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest mb-6">Penyimpanan Aman & Cepat</span>
                <h1 class="text-6xl md:text-7xl font-extrabold text-gray-900 leading-[1.1] mb-8">
                    Simpan Segala File di <span class="text-blue-600">Satu Tempat</span> Aman.
                </h1>
                <p class="text-xl text-gray-500 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Kelola, bagikan, dan akses dokumen Anda dari mana saja dengan kecepatan tinggi. CloudBox memberikan kontrol penuh atas data Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-blue-600 text-white rounded-2xl font-bold text-lg hover:bg-blue-700 hover:shadow-2xl hover:shadow-blue-200 transition transform hover:-translate-y-1">Mulai Sekarang</a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white text-gray-900 border-2 border-gray-100 rounded-2xl font-bold text-lg hover:bg-gray-50 transition">Lihat Fitur</a>
                </div>
            </div>
            
        </div>
    </section>

    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-4xl font-extrabold mb-4">Fitur Canggih untuk <br>Produktivitas Anda</h2>
                    <p class="text-gray-500 text-lg">Didesain khusus untuk kecepatan dan kemudahan penggunaan setiap hari.</p>
                </div>
                <div class="pb-2">
                    <span class="text-blue-600 font-bold border-b-2 border-blue-600 pb-1">Selengkapnya</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Chunk Upload</h3>
                    <p class="text-gray-500 leading-relaxed">Upload file berukuran GB tanpa takut gagal koneksi. Sistem kami memecah file menjadi bagian kecil.</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Easy Sharing</h3>
                    <p class="text-gray-500 leading-relaxed">Bagikan file atau satu folder sekaligus lewat link publik yang aman dan instan.</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Recycle Bin</h3>
                    <p class="text-gray-500 leading-relaxed">Salah hapus? Jangan panik. File Anda aman di Recycle Bin selama 30 hari sebelum dimusnahkan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-blue-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-blue-200">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 rounded-full -mr-20 -mt-20 opacity-50"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-700 rounded-full -ml-10 -mb-10 opacity-50"></div>
                
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 relative z-10">Siap Mengelola File <br>Dengan Lebih Baik?</h2>
                <div class="flex justify-center relative z-10">
                    <a href="{{ route('register') }}" class="px-12 py-5 bg-white text-blue-600 rounded-2xl font-black text-lg hover:bg-gray-100 transition shadow-xl transform hover:-translate-y-1">Daftar Sekarang</a>
                </div>
                <p class="mt-8 text-blue-100 font-medium relative z-10 opacity-80 italic">Gabung bersama ribuan pengguna lainnya.</p>
            </div>
        </div>
    </section>


</body>
</html>