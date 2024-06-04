<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'extension',
        'size',
        'mime',
        'folder_id',
        'user_id',
        'path',
        'location',
        'is_starred',
    ];

    // Define the relationship between File and Folder
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id')->withDefault(); 
    }

    // Define the relationship between File and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship between File and Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
