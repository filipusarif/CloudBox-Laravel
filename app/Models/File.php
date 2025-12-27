<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'folder_id', 'storage_config_id', 
        'name', 'path', 'size', 'extension', 'mime_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function storageConfig()
    {
        return $this->belongsTo(StorageConfig::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
