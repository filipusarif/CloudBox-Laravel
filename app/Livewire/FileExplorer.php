<?php
// app/Livewire/FileExplorer.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Folder;
use App\Models\File;
use App\Models\Share;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class FileExplorer extends Component
{
    public $currentFolderId = null;
    public $newFolderName; 
    public $showCreateFolderModal = false; 

    public function openFolder($id)
    {
        $this->currentFolderId = $id;
    }

    public function goBack()
    {
        if ($this->currentFolderId) {
            $folder = Folder::find($this->currentFolderId);
            $this->currentFolderId = $folder->parent_id;
        }
    }

    public function createFolder()
    {
        $this->validate([
            'newFolderName' => 'required|string|max:255',
        ]);

        \App\Models\Folder::create([
            'user_id' => auth()->id(),
            'parent_id' => $this->currentFolderId,
            'name' => $this->newFolderName,
        ]);

        $this->newFolderName = ''; 
        $this->showCreateFolderModal = false; 
    }

    public $viewMode = 'explorer';

    public function deleteFile($id)
    {
        File::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'File dipindahkan ke tempat sampah.');
    }

    public function deleteFolder($id)
    {
        Folder::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Folder dipindahkan ke tempat sampah.');
    }

    public function restoreItem($id, $type)
    {
        $model = $type === 'file' ? File::class : Folder::class;
        $model::withTrashed()->where('user_id', auth()->id())->findOrFail($id)->restore();
        session()->flash('message', 'Item berhasil dikembalikan.');
    }

    public function permanentDelete($id, $type)
    {
        if ($type === 'file') {
            $file = File::withTrashed()->where('user_id', auth()->id())->findOrFail($id);
            
            if (Storage::disk('private')->exists($file->path)) {
                Storage::disk('private')->delete($file->path);
            }
            
            auth()->user()->decrement('used_capacity', $file->size);
            $file->forceDelete();
        } else {
            Folder::withTrashed()->where('user_id', auth()->id())->findOrFail($id)->forceDelete();
        }
        session()->flash('message', 'Item dihapus permanen.');
    }

    public $editingId = null;
    public $editingType = null; 
    public $newName = '';

    public function startRename($id, $type, $currentName)
    {
        $this->editingId = $id;
        $this->editingType = $type;
        $this->newName = $currentName;
    }

    public function saveRename()
    {
        if ($this->editingType === 'file') {
            File::where('user_id', auth()->id())->find($this->editingId)->update(['name' => $this->newName]);
        } else {
            Folder::where('user_id', auth()->id())->find($this->editingId)->update(['name' => $this->newName]);
        }

        $this->editingId = null; 
    }


    public $showShareModal = false;
    public $selectedFileForShare = null;
    public $shareLink = '';

    public $shareType = ''; 

    public function openShareModal($id, $type)
    {
        $this->shareType = $type;
        
        $share = \App\Models\Share::where('user_id', auth()->id())
            ->where($type . '_id', $id) 
            ->first();

        if (!$share) {
            $share = \App\Models\Share::create([
                'user_id' => auth()->id(),
                'file_id' => $type === 'file' ? $id : null,
                'folder_id' => $type === 'folder' ? $id : null,
                'token' => \Illuminate\Support\Str::random(12),
                'expires_at' => now()->addDays(7),
            ]);
        }

        $this->shareLink = route('share.show', $share->token);
        $this->showShareModal = true;
    }

    public $selectedFileForPreview = null;

    public function openPreview($fileId)
    {
        $this->selectedFileForPreview = File::find($fileId);
    }

    public function closePreview()
    {
        $this->selectedFileForPreview = null;
    }

    public $showMoveModal = false;
    public $movingItemId = null;
    public $movingItemType = null; 
    public $movingItemName = '';
    public $targetFolderId = null;
    public $modalCurrentFolderId = null;

    public function startMove($id, $type, $name)
    {
        $this->movingItemId = $id;
        $this->movingItemType = $type;
        $this->movingItemName = $name;
        $this->targetFolderId = null;
        $this->modalCurrentFolderId = null; 
        $this->showMoveModal = true;
    }

    public function enterModalFolder($id)
    {
        $this->modalCurrentFolderId = $id;
    }

    public function goBackModal()
    {
        if ($this->modalCurrentFolderId) {
            $folder = \App\Models\Folder::find($this->modalCurrentFolderId);
            $this->modalCurrentFolderId = $folder->parent_id;
        }
    }

    public function setTargetFolder($id)
    {
        if ($this->movingItemType === 'folder' && $this->movingItemId == $id) {
            return;
        }
        $this->targetFolderId = $id;
    }

    public function moveItem()
    {
        if ($this->movingItemType === 'file') {
            \App\Models\File::where('user_id', auth()->id())
                ->where('id', $this->movingItemId)
                ->update(['folder_id' => $this->targetFolderId]);
        } else {
            \App\Models\Folder::where('user_id', auth()->id())
                ->where('id', $this->movingItemId)
                ->update(['parent_id' => $this->targetFolderId]);
        }

        $this->showMoveModal = false;
        session()->flash('message', "{$this->movingItemName} berhasil dipindahkan.");
    }

    #[On('refresh-explorer')] 
    public function refreshExplorer()
    {
        // auto refresh
    }

    public $search = ''; 

    public function clearSearch()
    {
        $this->search = '';
    }

    #[Url] 
    public $category = null;

    public function render()
    {
        $user = Auth::user();

        $folderQuery = \App\Models\Folder::where('user_id', $user->id);
        $fileQuery = \App\Models\File::where('user_id', $user->id);


        $breadcrumbs = [];
        if ($this->currentFolderId && !$this->search && !$this->category) {
            $tempFolderId = $this->currentFolderId;
            while ($tempFolderId) {
                $f = \App\Models\Folder::find($tempFolderId);
                if ($f) {
                    array_unshift($breadcrumbs, [
                        'id' => $f->id,
                        'name' => $f->name
                    ]);
                    $tempFolderId = $f->parent_id;
                } else {
                    $tempFolderId = null;
                }
            }
        }

        if ($this->category) {
            $extensions = match($this->category) {
                'photo' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
                'video' => ['mp4', 'avi', 'mov', 'mkv', 'wmv'],
                'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'],
                'audio' => ['mp3', 'wav', 'flac', 'ogg'],
                default => []
            };

            $fileQuery->whereIn('extension', $extensions);
            $folders = collect(); 
        } else {
            if ($this->viewMode === 'trash') {
                $folderQuery->onlyTrashed();
                $fileQuery->onlyTrashed();
            } else {
                if ($this->search) {
                    $folderQuery->where('name', 'like', '%' . $this->search . '%');
                    $fileQuery->where('name', 'like', '%' . $this->search . '%');
                } else {
                    $folderQuery->where('parent_id', $this->currentFolderId);
                    $fileQuery->where('folder_id', $this->currentFolderId);
                }
            }
            $folders = $folderQuery->get();
        }

        return view('livewire.file-explorer', [
            'folders' => $folders,
            'files' => $fileQuery->get(),
            'breadcrumbs' => $breadcrumbs,
    ]);
    }
}