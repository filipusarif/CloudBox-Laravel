<?php
// app/Services/FileService.php
namespace App\Services;
use Illuminate\Http\File as LaravelFile;
use App\Models\File;
use App\Models\StorageConfig;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Logika utama untuk menangani upload file.
     */
    public function upload($file, User $user, $folderId = null, $customName = null)
    {
        $storageConfig = StorageConfig::where('is_active', true)->first();
        
        if (!$storageConfig) {
            throw new \Exception("Tidak ada konfigurasi penyimpanan yang aktif.");
        }

        $originalName = $customName ?: (
            $file instanceof UploadedFile 
                ? $file->getClientOriginalName() 
                : $file->getFilename()
        );

        $fileSize = $file->getSize();

        if (($user->used_capacity + $fileSize) > $user->total_capacity) {
            throw new \Exception("Kuota penyimpanan tidak mencukupi.");
        }

        return DB::transaction(function () use ($file, $user, $folderId, $storageConfig, $originalName, $fileSize) {
            
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $filename = Str::uuid() . '.' . $extension;
            $path = "uploads/user_{$user->id}/" . $filename;

            Storage::disk($storageConfig->driver)->putFileAs(
                "uploads/user_{$user->id}", 
                $file, 
                $filename
            );

            $user->increment('used_capacity', $fileSize);


            return File::create([
                'user_id' => $user->id,
                'folder_id' => $folderId,
                'storage_config_id' => $storageConfig->id,
                'name' => $originalName,
                'path' => $path,
                'size' => $fileSize,
                'extension' => $extension,
                'mime_type' => $file->getMimeType(),
            ]);

        });
    }

    public function handleChunkUpload($file, $identifier, $chunkIndex, $totalChunks)
    {
        $tempDir = storage_path("app/chunks/{$identifier}");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $file->move($tempDir, $chunkIndex);

        if ($chunkIndex + 1 == $totalChunks) {
            return $this->mergeChunks($identifier, $totalChunks);
        }

        return ['status' => 'uploading', 'chunk' => $chunkIndex];
    }

    protected function mergeChunks($identifier, $totalChunks)
    {
        $tempDir = storage_path("app/chunks/{$identifier}");
        $finalPath = storage_path("app/chunks/{$identifier}.merged");

        $out = fopen($finalPath, "wb");

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "{$tempDir}/{$i}";
            $in = fopen($chunkPath, "rb");
            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }
            fclose($in);
            unlink($chunkPath); 
        }

        fclose($out);
        rmdir($tempDir); 

        return new \Illuminate\Http\File($finalPath);
    }
}