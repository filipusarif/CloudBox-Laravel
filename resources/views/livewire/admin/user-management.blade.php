<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">User Management</h2>
        <input wire:model.live="search" type="text" placeholder="Cari user..." class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 w-64">
        <button wire:click="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            + Add User
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md shadow-sm border border-green-200">{{ session('message') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md shadow-sm border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyimpanan (Used/Total)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            @if($user->is_admin)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                    Admin
                                </span>
                            @endif
                        </div>
                        <div class="text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $percent = ($user->used_capacity / $user->total_capacity) * 100;
                            $color = $percent > 90 ? 'bg-red-500' : ($percent > 70 ? 'bg-yellow-500' : 'bg-blue-500');
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                            <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ number_format($user->used_capacity / 1024 / 1024 / 1024, 2) }} GB / 
                            {{ number_format($user->total_capacity / 1024 / 1024 / 1024, 0) }} GB ({{ round($percent, 1) }}%)
                        </div>
                    </td>
                    
                    <td class="px-6 py-4">
                        <button wire:click="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</button>
                        <button onclick="confirm('Hapus user ini? Seluruh file mereka akan hilang selamanya!') || event.stopImmediatePropagation()" 
                                wire:click="deleteUser({{ $user->id }})" 
                                class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $users->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $editingUserId ? 'Edit User' : 'Tambah User Baru' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <input type="email" wire:model="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password {{ $editingUserId ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kuota Penyimpanan (GB)</label>
                        <input type="number" wire:model="newQuotaGb" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                        @error('newQuotaGb') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4 flex items-center">
                        <input type="checkbox" wire:model="is_admin" id="is_admin" 
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 h-5 w-5">
                        <label for="is_admin" class="ml-2 block text-sm text-gray-900 font-semibold">
                            Berikan Akses Administrator
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-7 italic">Admin dapat mengakses dashboard ini dan mengelola user lain.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <button wire:click="closeModal" class="text-gray-600 font-semibold px-4 py-2">Batal</button>
                <button wire:click="{{ $editingUserId ? 'updateUser' : 'createUser' }}" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 shadow-md">
                    {{ $editingUserId ? 'Simpan Perubahan' : 'Buat User' }}
                </button>
            </div>
        </div>
    </div>
    @endif


</div>