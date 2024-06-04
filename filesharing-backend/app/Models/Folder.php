<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'user_id',
        'path',
        'parent_id',
        'nesting_level',
    ];

    // Boot method for model event handling
    protected static function boot()
    {
        parent::boot();
    
        // Before creating a folder, set the nesting level and path
        static::creating(function ($folder) {
            if ($folder->parent_id) {
                // If the folder has a parent, find the parent folder
                $parentFolder = self::find($folder->parent_id);
                
                // If the parent folder is not found, throw an exception
                if (!$parentFolder) {
                    throw new \Exception('Parent folder not found.');
                }
                
                // If the parent folder's nesting level is 6 or more, throw an exception
                if ($parentFolder->nesting_level >= 6) {
                    throw new \Exception('Maximum nesting level of 6 exceeded.');
                }
                
                // Set the nesting level of the new folder to be one more than the parent's nesting level
                $folder->nesting_level = $parentFolder->nesting_level + 1;
                
                // Set the path of the new folder based on the parent's path
                $folder->path = $parentFolder->path . '/' . $folder->name;
            } else {
                // If the folder has no parent, it is a root folder
                $folder->nesting_level = 1;
                $folder->path = '/storage/' . $folder->name;
            }
        });
    }
    

    // Define the relationship between Folder and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Define the relationship between Folder and File
    public function files()
    {
        return $this->hasMany(File::class)->whereNull('folder_id'); // Include files at the root level
    }
    
    // Define the relationship between Folder and its children (subfolders)
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }
    
    // Define the relationship between Folder and its parent
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id')->withDefault(); // Allow folder to belong to null parent
    }
}
