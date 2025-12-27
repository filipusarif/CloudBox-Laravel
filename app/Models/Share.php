<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'user_id', 'file_id', 'folder_id', 
        'token', 'password', 'expires_at', 'clicks'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
