<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    // public function fileShare()
    // {
    //     return $this->belongsTo(FileShare::class, 'file_id', 'file_id')->where('shared_with_user_id', auth()->id());
    // }
}
