<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'path',
        'parent_id',
        'nesting_level',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($folder) {
            if ($folder->parent_id) {
                $parentFolder = self::find($folder->parent_id);
                if (!$parentFolder) {
                    throw new \Exception('Parent folder not found.');
                }
                if ($parentFolder->nesting_level >= 6) {
                    throw new \Exception('Maximum nesting level of 6 exceeded.');
                }
                $folder->nesting_level = $parentFolder->nesting_level + 1;
                $folder->path = $parentFolder->path . '/' . $folder->name;
            } else {
                $folder->nesting_level = 1;
                $folder->path = '/storage/' . $folder->name;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function files()
    {
        return $this->hasMany(File::class)->whereNull('folder_id'); // include files at the root level
    }
    
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }
    
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id')->withDefault(); // allow folder to belong to null parent
    }
    
}
