 <!-- resources/views/livewire/file-explorer.blade.php -->

<div class="p-6">

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="relative flex-1 max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </span>
        <input wire:model.live.debounce.300ms="search" type="text" 
            placeholder="Cari file atau folder..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all shadow-sm">
        
        @if($search)
            <button wire:click="clearSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500">
                ✕
            </button>
        @endif
    </div>

    @if($search)
        <div class="text-sm text-gray-500">
            Menampilkan hasil pencarian untuk: <span class="font-bold text-blue-600">"{{ $search }}"</span>
        </div>
    @endif
</div>

    <div class="flex space-x-4 mb-6 border-b">
        <button wire:click="$set('viewMode', 'explorer')" 
            class="pb-2 px-4 transition-colors {{ $viewMode === 'explorer' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-gray-500 hover:text-blue-500' }}">
            📁 My Files
        </button>
        <button wire:click="$set('viewMode', 'trash')" 
            class="pb-2 px-4 transition-colors {{ $viewMode === 'trash' ? 'border-b-2 border-red-600 text-red-600 font-bold' : 'text-gray-500 hover:text-red-500' }}">
            🗑 Recycle Bin
        </button>
    </div>

    <div class="flex items-center justify-between mb-6">
        <!-- <h2 class="text-2xl font-bold text-gray-800">
            {{ $viewMode === 'trash' ? 'Recycle Bin' : ($currentFolderId ? 'Inside Folder' : 'Root Storage') }}
        </h2> -->
        <div class="flex items-center space-x-2 overflow-hidden">
            <button wire:click="openFolder(null)" class="flex items-center text-gray-500 hover:text-blue-600 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-2 font-bold hidden md:block">My Files</span>
            </button>

            @if($breadcrumbs)
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            @endif

            <nav class="flex items-center space-x-2 overflow-hidden">
                @foreach($breadcrumbs as $index => $breadcrumb)
                    <div class="flex items-center space-x-2 overflow-hidden">
                        <button wire:click="openFolder({{ $breadcrumb['id'] }})" 
                            class="text-sm font-medium truncate max-w-[100px] md:max-w-xs {{ $loop->last ? 'text-gray-800 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                            {{ $breadcrumb['name'] }}
                        </button>
                        
                        @if(!$loop->last)
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        @endif
                    </div>
                @endforeach
            </nav>

            @if($search)
                <span class="text-sm font-bold text-blue-600">/ Hasil Pencarian</span>
            @elseif($category)
                <span class="text-sm font-bold text-blue-600">/ Kategori: {{ ucfirst($category) }}</span>
            @elseif($viewMode === 'trash')
                <span class="text-sm font-bold text-red-600">/ Recycle Bin</span>
            @endif
        </div>
        
        <div class="space-x-2">
            @if($viewMode === 'explorer')
                @if($currentFolderId)
                    <button wire:click="goBack" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                        ← Back
                    </button>
                @endif

                <button wire:click="$set('showCreateFolderModal', true)" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition shadow-sm">+ New Folder</button>
                
                <label class="px-4 py-2 bg-blue-600 text-white rounded cursor-pointer hover:bg-blue-700 transition shadow-sm">
                    <span>↑ Upload File</span>
                    <input type="file" id="fileInput" class="hidden" onchange="startChunkUpload(this, {{ $currentFolderId ?? 'null' }})">
                </label>
            @else
                <span class="text-sm text-gray-500 italic">File di sini akan dihapus otomatis setelah 30 hari.</span>
            @endif
        </div>
    </div>

    <div id="progressContainer" class="hidden mt-4 p-4 border rounded bg-blue-50 border-blue-100 mb-6">
        <div class="flex justify-between mb-2">
            <span id="progressFileName" class="text-sm font-bold text-blue-700 truncate w-2/3">Uploading...</span>
            <span id="progressPercent" class="text-sm font-bold text-blue-700">0%</span>
        </div>
        <div class="w-full bg-blue-200 rounded-full h-3">
            <div id="progressBar" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        
        @foreach($folders as $folder)
            <div wire:key="folder-{{ $folder->id }}" class="group relative p-4 border rounded-xl flex flex-col items-center hover:bg-gray-50 transition shadow-sm bg-white">
                <div @if($viewMode === 'explorer') wire:click="openFolder({{ $folder->id }})" @endif class="{{ $viewMode === 'explorer' ? 'cursor-pointer' : '' }} flex flex-col items-center">
                    <svg class="w-16 h-16 {{ $viewMode === 'trash' ? 'text-gray-300' : 'text-yellow-500' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                    </svg>
                    <span class="mt-2 text-xs text-center font-medium text-gray-700 w-full px-2 line-clamp-2 break-all" 
                        title="{{ $folder->name }}">
                        {{ $folder->name }}
                    </span>
                </div>
                
                <div class="absolute top-2 right-2 hidden group-hover:flex space-x-1">
                    @if($viewMode === 'trash')
                        <button wire:click="restoreItem({{ $folder->id }}, 'folder')" class="p-1.5 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600" title="Restore">↺</button>
                        <button onclick="confirm('Hapus folder secara permanen? Tindakan ini tidak bisa dibatalkan.') || event.stopImmediatePropagation()" 
                                wire:click="permanentDelete({{ $folder->id }}, 'folder')" 
                                class="p-1.5 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700" title="Delete Permanently">✕</button>
                    @else
                        <button wire:click="openShareModal({{ $folder->id }}, 'folder')" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        </button>
                        <button wire:click="startRename({{ $folder->id }}, 'folder', '{{ $folder->name }}')" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-blue-600">✎</button>
                        <button wire:click="deleteFolder({{ $folder->id }})" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-red-600">🗑</button>
                    @endif
                </div>
            </div>
        @endforeach

        @foreach($files as $file)
            <div wire:key="file-{{ $file->id }}" class="group relative p-4 border rounded-xl flex flex-col items-center hover:shadow-md transition bg-white">
                <div @if($viewMode === 'explorer') wire:click="openPreview({{ $file->id }})" @endif class="{{ $viewMode === 'explorer' ? 'cursor-pointer' : '' }} flex flex-col items-center">
                    <div class="w-16 h-16 flex items-center justify-center">
                        @if(in_array($file->extension, ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg']))
                            <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @elseif(in_array($file->extension, ['mp4', 'webm', 'mov']))
                            <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        @elseif(in_array($file->extension, ['mp3', 'wav', 'ogg']))
                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"></path></svg>
                        @elseif($file->extension === 'pdf')
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium text-gray-700 w-full px-2 line-clamp-2 break-all" 
                        title="{{ $file->name }}">
                        {{ $file->name }}
                    </span>
                </div>

                <div class="absolute top-2 right-2 hidden group-hover:flex space-x-1">
                    @if($viewMode === 'trash')
                        <button wire:click="restoreItem({{ $file->id }}, 'file')" class="p-1.5 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600" title="Restore">↺</button>
                        <button onclick="confirm('Hapus file secara permanen?') || event.stopImmediatePropagation()" 
                                wire:click="permanentDelete({{ $file->id }}, 'file')" 
                                class="p-1.5 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700" title="Delete Permanently">✕</button>
                    @else
                        <button wire:click="openShareModal({{ $file->id }}, 'file')" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        </button>
                        <button wire:click="startMove({{ $file->id }}, 'file', '{{ $file->name }}')" 
                                class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-orange-600" title="Pindah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </button>
                        <button wire:click="startRename({{ $file->id }}, 'file', '{{ $file->name }}')" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-blue-600">✎</button>
                        <button wire:click="deleteFile({{ $file->id }})" class="p-1.5 bg-white border rounded-md shadow-sm text-gray-600 hover:text-red-600">🗑</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($folders->isEmpty() && $files->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1h1a2 2 0 012 2v10a2 2 0 01-2 2H5z"></path></svg>
            <p>{{ $viewMode === 'trash' ? 'Tempat sampah kosong.' : 'Folder ini kosong.' }}</p>
        </div>
    @endif

    @if($showCreateFolderModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Buat Folder Baru</h3>
            <input type="text" wire:model="newFolderName" class="w-full border rounded-lg p-2.5 mb-4 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama folder...">
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showCreateFolderModal', false)" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                <button wire:click="createFolder" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    @if($selectedFileForPreview)
    <div class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] flex flex-col overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <div class="flex items-center space-x-3 truncate">
                    <span class="p-2 bg-blue-100 text-blue-600 rounded">
                        @if(in_array($selectedFileForPreview->extension, ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'])) 🖼️ 
                        @elseif(in_array($selectedFileForPreview->extension, ['mp4', 'webm'])) 🎬 
                        @elseif(in_array($selectedFileForPreview->extension, ['mp3', 'wav'])) 🎵 
                        @elseif($selectedFileForPreview->extension === 'pdf') 📄 
                        @else 📁 @endif
                    </span>
                    <h3 class="font-bold text-gray-800 truncate">{{ $selectedFileForPreview->name }}</h3>
                </div>
                <button wire:click="closePreview" class="text-gray-400 hover:text-red-600 text-2xl transition">✕</button>
            </div>
            
            <div class="flex-1 overflow-auto p-4 bg-gray-900 flex justify-center items-center min-h-[300px]">
                
                {{-- 1. PREVIEW FOTO --}}
                @if(in_array($selectedFileForPreview->extension, ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg']))
                    <img src="{{ route('file.view', $selectedFileForPreview->id) }}" 
                        class="max-w-full max-h-[75vh] object-contain shadow-2xl animate-in zoom-in duration-300">

                {{-- 2. PREVIEW VIDEO --}}
                @elseif(in_array($selectedFileForPreview->extension, ['mp4', 'webm', 'ogg']))
                    <video controls crossorigin playsinline class="w-full max-h-[75vh] outline-none">
                        <source src="{{ route('file.view', $selectedFileForPreview->id) }}" type="video/{{ $selectedFileForPreview->extension }}">
                        Browser Anda tidak mendukung tag video.
                    </video>

                {{-- 3. PREVIEW AUDIO/MUSIK --}}
                @elseif(in_array($selectedFileForPreview->extension, ['mp3', 'wav', 'ogg']))
                    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl text-center">
                        <div class="mb-6 animate-pulse">
                            <span class="text-6xl">🎵</span>
                        </div>
                        <h4 class="mb-4 text-gray-600 font-medium">{{ $selectedFileForPreview->name }}</h4>
                        <audio controls class="w-full">
                            <source src="{{ route('file.view', $selectedFileForPreview->id) }}" type="audio/{{ $selectedFileForPreview->extension }}">
                            Browser Anda tidak mendukung tag audio.
                        </audio>
                    </div>

                {{-- 4. PREVIEW PDF --}}
                @elseif($selectedFileForPreview->extension === 'pdf')
                    <iframe src="{{ route('file.view', $selectedFileForPreview->id) }}" 
                            class="w-full h-[75vh] rounded shadow-inner bg-white" 
                            frameborder="0"></iframe>

                {{-- 5. JIKA TIPE FILE TIDAK DIDUKUNG PREVIEW --}}
                @else
                    <div class="text-center p-12 bg-white rounded-2xl shadow-xl max-w-sm">
                        <div class="text-5xl mb-4">📎</div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Preview Tidak Tersedia</h3>
                        <p class="text-sm text-gray-500 mb-6">File <strong>.{{ $selectedFileForPreview->extension }}</strong> tidak dapat ditampilkan secara langsung.</p>
                        <a href="{{ route('file.view', $selectedFileForPreview->id) }}" download 
                        class="inline-block w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
                            Download File
                        </a>
                    </div>
                @endif
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-between items-center text-xs text-gray-500">
                <span>Ukuran: {{ number_format($selectedFileForPreview->size / 1024 / 1024, 2) }} MB</span>
                <span>Tipe: {{ strtoupper($selectedFileForPreview->extension) }}</span>
            </div>
        </div>
    </div>
    @endif

    @if($showMoveModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[70] p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800">Pindahkan "{{ $movingItemName }}"</h3>
                    <p class="text-xs text-gray-500">Lokasi: {{ $modalCurrentFolderId ? \App\Models\Folder::find($modalCurrentFolderId)->name : 'Root' }}</p>
                </div>
                @if($modalCurrentFolderId)
                    <button wire:click="goBackModal" class="text-sm text-blue-600 hover:underline">← Kembali</button>
                @endif
            </div>

            <div class="p-4 max-h-80 overflow-y-auto bg-white">
                @if(!$modalCurrentFolderId)
                    <div wire:click="setTargetFolder(null)" 
                        class="p-3 mb-2 rounded-lg cursor-pointer border flex items-center transition {{ $targetFolderId === null ? 'bg-blue-50 border-blue-400 shadow-sm' : 'hover:bg-gray-50 border-gray-100' }}">
                        <div class="flex-1 flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            <span class="text-sm font-bold">Pilih Root Storage</span>
                        </div>
                    </div>
                @else
                    <div wire:click="setTargetFolder({{ $modalCurrentFolderId }})" 
                        class="p-3 mb-2 rounded-lg cursor-pointer border flex items-center transition {{ $targetFolderId == $modalCurrentFolderId ? 'bg-blue-50 border-blue-400 shadow-sm' : 'hover:bg-gray-50 border-gray-100' }}">
                        <div class="flex-1 flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            <span class="text-sm font-bold">Pilih folder "{{ \App\Models\Folder::find($modalCurrentFolderId)->name }}"</span>
                        </div>
                    </div>
                @endif

                <hr class="my-3 border-gray-100">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 mb-2">Sub-folder:</p>

                @php
                    $subFoldersInModal = \App\Models\Folder::where('user_id', auth()->id())
                        ->where('parent_id', $modalCurrentFolderId)
                        ->where('id', '!=', $movingItemId) // Sembunyikan folder yang sedang dipindah
                        ->get();
                @endphp

                @forelse($subFoldersInModal as $sub)
                    <div class="flex items-center group mb-1">
                        <div wire:click="setTargetFolder({{ $sub->id }})" 
                            class="flex-1 p-3 rounded-l-lg cursor-pointer border border-r-0 flex items-center transition {{ $targetFolderId == $sub->id ? 'bg-blue-50 border-blue-400' : 'hover:bg-gray-50 border-gray-100' }}">
                            <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            <span class="text-sm truncate">{{ $sub->name }}</span>
                        </div>
                        <button wire:click="enterModalFolder({{ $sub->id }})" 
                            class="p-3 bg-gray-50 border border-l-0 rounded-r-lg hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition" 
                            title="Masuk ke folder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-gray-400 italic">Tidak ada sub-folder lagi.</p>
                @endforelse
            </div>

            <div class="p-4 bg-gray-50 flex justify-end space-x-2">
                <button wire:click="$set('showMoveModal', false)" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-200 rounded-lg">Batal</button>
                <button wire:click="moveItem" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-md">
                    Pindahkan ke Sini
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($editingId)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Ubah Nama</h3>
            <input type="text" wire:model="newName" class="w-full border rounded-lg p-2.5 mb-4 focus:ring-2 focus:ring-blue-500 outline-none">
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('editingId', null)" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                <button wire:click="saveRename" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    @if($showShareModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">
            <h3 class="text-lg font-bold mb-2">Bagikan {{ $shareType === 'file' ? 'File' : 'Folder' }}</h3>
            <p class="text-sm text-gray-500 mb-4">Siapa pun yang memiliki link ini dapat melihat/mengunduh.</p>
            <div class="flex items-center space-x-2 mb-6">
                <input type="text" readonly value="{{ $shareLink }}" class="flex-1 border rounded-lg p-2 bg-gray-50 text-sm outline-none">
                <button onclick="navigator.clipboard.writeText('{{ $shareLink }}'); alert('Link disalin!')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700">Salin</button>
            </div>
            <div class="flex justify-end">
                <button wire:click="$set('showShareModal', false)" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">Tutup</button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
async function startChunkUpload(input, folderId) {
    const file = input.files[0];
    if (!file) return;

    const chunkSize = 2 * 1024 * 1024; // 2MB
    const totalChunks = Math.ceil(file.size / chunkSize);
    const identifier = Math.random().toString(36).substring(2) + Date.now();

    const container = document.getElementById('progressContainer');
    const bar = document.getElementById('progressBar');
    const percentText = document.getElementById('progressPercent');
    const nameText = document.getElementById('progressFileName');
    
    container.classList.remove('hidden');
    nameText.innerText = file.name;

    for (let i = 0; i < totalChunks; i++) {
        const start = i * chunkSize;
        const end = Math.min(file.size, start + chunkSize);
        const chunk = file.slice(start, end);

        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('identifier', identifier);
        formData.append('chunk_index', i);
        formData.append('total_chunks', totalChunks);
        formData.append('filename', file.name);
        formData.append('folder_id', folderId !== null ? folderId : '');

        try {
            const response = await fetch('/upload-chunk', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            const progress = Math.round(((i + 1) / totalChunks) * 100);
            bar.style.width = progress + '%';
            percentText.innerText = progress + '%';

            if (result.status === 'completed') {
                window.Livewire.dispatch('refresh-explorer');
                setTimeout(() => {
                    container.classList.add('hidden');
                    bar.style.width = '0%';
                }, 2000);
            }
        } catch (error) {
            alert('Upload gagal, silakan coba lagi.');
            container.classList.add('hidden');
            break;
        }
    }
    input.value = ''; 
}
</script>