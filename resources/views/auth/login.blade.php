<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition shadow-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex justify-between mb-1">
                <label for="password" class="text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-blue-600 hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition shadow-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600 font-medium">Ingat saya</label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-100 transition transform active:scale-95">
                Masuk Sekarang
            </button>
        </div>

        <div class="text-center pt-4">
            <p class="text-sm text-gray-500">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar Gratis</a></p>
        </div>
    </form>
</x-guest-layout>