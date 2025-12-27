<?php
// app/Http/Controllers/FileController.php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function store(UploadFileRequest $request, FileService $fileService)
    {
        try {
            $file = $fileService->upload(
                $request->file('file'),
                Auth::user(),
                $request->folder_id
            );

            return back()->with('success', 'File berhasil diunggah: ' . $file->name);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function uploadChunk(Request $request, FileService $fileService)
    {
        $request->validate([
            'file' => 'required|file',
            'identifier' => 'required|string', 
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'filename' => 'required|string',
        ]);

        $result = $fileService->handleChunkUpload(
            $request->file('file'),
            $request->identifier,
            $request->chunk_index,
            $request->total_chunks
        );

        if ($result instanceof \Illuminate\Http\File) {
            $folderId = $request->folder_id ?: null;
            $finalFile = $fileService->upload(
                $result, 
                Auth::user(), 
                $folderId, 
                $request->filename 
            );
            
            unlink($result->getPathname());

            return response()->json(['status' => 'completed', 'file' => $finalFile]);
        }

        return response()->json($result);
    }

    public function preview(\App\Models\File $file)
    {
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        $path = storage_path('app/private/' . $file->path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->name . '"'
        ]);
    }
}
