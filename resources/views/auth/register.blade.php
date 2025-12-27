<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-2">Dapatkan kuota gratis 1GB sekarang</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-100 transition transform active:scale-95">
                Daftar & Mulai
            </button>
        </div>

        <div class="text-center pt-4">
            <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a></p>
        </div>
    </form>
</x-guest-layout>