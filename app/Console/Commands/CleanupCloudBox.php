<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

class CleanupCloudBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudbox:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan chunks gagal dan link share yang kadaluwarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pembersihan...');

        $chunkPath = 'chunks'; 
        $disk = Storage::disk('private');

        if ($disk->exists($chunkPath)) {
            $directories = $disk->directories($chunkPath);
            $now = Carbon::now();
            $deletedCount = 0;

            foreach ($directories as $dir) {
                $lastModified = Carbon::createFromTimestamp($disk->lastModified($dir));

                if ($lastModified->diffInHours($now) >= 24) {
                    $disk->deleteDirectory($dir);
                    $deletedCount++;
                }
            }
            $this->info("Berhasil menghapus {$deletedCount} folder chunk sampah.");
        }

        $expiredShares = Share::where('expires_at', '<', now())->delete();
        $this->info("Berhasil menghapus {$expiredShares} link share yang kadaluwarsa.");

        $oldFiles = File::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->get();

        foreach ($oldFiles as $file) {
            Storage::disk('private')->delete($file->path);
            $file->forceDelete();
        }
        $this->info('Pembersihan selesai!');
    }
}
