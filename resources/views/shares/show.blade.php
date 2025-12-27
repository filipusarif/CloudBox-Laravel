<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CloudBox - Berbagi File</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <nav class="bg-white border-b border-gray-100 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                <span class="text-xl font-bold tracking-tight">CloudBox</span>
            </div>
            <span class="text-xs text-gray-400">Public Shared Link</span>
        </div>
    </nav>

    <div class="min-h-[80vh] flex items-center justify-center p-6">
        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-2xl w-full border border-gray-100">
            
            @if($share->file)
                <div class="text-center">
                    <div class="text-blue-500 mb-6 flex justify-center">
                        <div class="p-4 bg-blue-50 rounded-full">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 break-words mb-1">{{ $share->file->name }}</h2>
                    <p class="text-gray-400 font-medium mb-8">{{ number_format($share->file->size / 1024 / 1024, 2) }} MB</p>
                    
                    <a href="{{ route('share.download', $share->token) }}" class="inline-flex items-center justify-center px-10 py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 group">
                        <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download File
                    </a>
                </div>
            @elseif($share->folder)
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-800">Folder: {{ $currentFolder->name }}</h2>
                        <nav class="flex text-xs text-gray-400 mt-1 space-x-1">
                            @if($currentFolder->id !== $share->folder_id)
                                <a href="{{ route('share.show', $share->token) }}" class="text-blue-500 hover:underline">Root</a>
                                <span>/</span>
                                <span class="truncate max-w-[100px]">...</span>
                                <span>/</span>
                                <span class="text-gray-600 font-bold">{{ $currentFolder->name }}</span>
                            @else
                                <span class="text-gray-600 font-bold italic underline decoration-blue-500">Root Directory</span>
                            @endif
                        </nav>
                    </div>
                    
                    <a href="{{ route('share.downloadFolder', $share->token) }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                        Download .ZIP
                    </a>
                </div>

                <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    @if($currentFolder->id !== $share->folder_id)
                        <a href="{{ route('share.show', ['token' => $share->token, 'folder' => $currentFolder->parent_id]) }}" 
                        class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:border-blue-400 transition text-sm font-bold text-blue-600 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    @endif

                    @foreach($subFolders as $sub)
                        <a href="{{ route('share.show', ['token' => $share->token, 'folder' => $sub->id]) }}" 
                        class="flex items-center p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md transition group">
                            <svg class="w-8 h-8 text-yellow-500 mr-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 truncate flex-1">{{ $sub->name }}</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @endforeach

                    @foreach($files as $file)
                        <div class="flex justify-between items-center p-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="text-sm font-bold text-gray-700 truncate max-w-[150px] md:max-w-xs">{{ $file->name }}</span>
                            </div>
                            <span class="text-xs font-mono text-gray-400">{{ number_format($file->size / 1024, 0) }} KB</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-10 pt-6 border-t border-gray-50 text-center">
                <p class="text-xs text-gray-400">Dibagikan oleh</p>
                <div class="mt-2 flex items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px] mr-2">
                        {{ substr($share->user->name, 0, 1) }}
                    </div>
                    <span class="text-sm font-bold text-gray-700">{{ $share->user->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-10">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">&copy; 2025 CloudBox Storage System</p>
    </footer>

</body>
</html>