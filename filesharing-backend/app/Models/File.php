<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'extension',
        'size',
        'mime',
        'folder_id',
        'user_id',
        'path',
        'location',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id')->withDefault(); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
