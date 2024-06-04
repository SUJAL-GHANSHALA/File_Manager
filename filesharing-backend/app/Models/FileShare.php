<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileShare extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'file_id',
        'shared_with_user_id',
        'access_control',
        'token', 
    ];

    // Default attribute values
    protected $attributes = [
        'token' => null, 
    ];

    // Define the relationship between FileShare and File
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    // Define the relationship between FileShare and User (who shared the file)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship between FileShare and User (with whom the file is shared)
    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }
}
