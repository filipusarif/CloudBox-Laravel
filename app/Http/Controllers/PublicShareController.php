<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Folder;
use App\Models\File as FileModel;
use App\Models\File;

class PublicShareController extends Controller
{
    public function show(Request $request, $token)
{
    $share = \App\Models\Share::with(['file', 'folder', 'user'])->where('token', $token)->firstOrFail();

    if ($share->expires_at && $share->expires_at->isPast()) {
        abort(404, 'Link sudah kedaluwarsa.');
    }

    $files = collect();
    $subFolders = collect();
    $currentFolder = $share->folder;

    if ($share->folder_id) {
        if ($request->has('folder')) {
            $requestedFolderId = $request->query('folder');
            
            
            $targetFolder = Folder::where('id', $requestedFolderId)
                                  ->where('user_id', $share->user_id)
                                  ->firstOrFail();
            
            $currentFolder = $targetFolder;
        }

        $files = File::where('folder_id', $currentFolder->id)->get();
        $subFolders = Folder::where('parent_id', $currentFolder->id)->get();
    }

    return view('shares.show', compact('share', 'files', 'subFolders', 'currentFolder'));
}

    public function download($token)
    {
        $share = Share::where('token', $token)->firstOrFail();
        $file = $share->file;

        $share->increment('clicks');

        return Storage::disk($file->storageConfig->driver)->download($file->path, $file->name);
    }

    public function downloadFolder($token)
    {
        $share = \App\Models\Share::with(['folder'])->where('token', $token)->firstOrFail();
        
        if (!$share->folder_id) {
            abort(404, 'Ini bukan link folder.');
        }

        $mainFolder = $share->folder;
        $zipFileName = $mainFolder->name . '.zip';
        $zipPath = storage_path('app/private/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/private/temp'))) {
            mkdir(storage_path('app/private/temp'), 0777, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Panggil fungsi rekursif dimulai dari folder utama
            $this->addFolderToZip($mainFolder, $zip, '');
            $zip->close();
        }

        $share->increment('clicks');

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Fungsi Rekursif untuk menyusun struktur folder ke dalam ZIP
     */
    protected function addFolderToZip($folder, $zip, $currentPathInZip)
    {
        $newPath = $currentPathInZip . $folder->name . '/';
        
        $zip->addEmptyDir($newPath);

        $files = FileModel::where('folder_id', $folder->id)->get();
        foreach ($files as $file) {
            $physicalPath = storage_path('app/private/' . $file->path);
            if (file_exists($physicalPath)) {
                $zip->addFile($physicalPath, $newPath . $file->name);
            }
        }

        $subFolders = Folder::where('parent_id', $folder->id)->get();
        foreach ($subFolders as $subFolder) {
            $this->addFolderToZip($subFolder, $zip, $newPath);
        }
    }
}
