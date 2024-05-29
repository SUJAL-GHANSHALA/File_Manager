<?php

namespace App\Http\Controllers;

use App\Models\FileShare;
use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class FileShareController extends Controller
{

    public function generateLink(Request $request, $fileId)
    {
        $file = File::findOrFail($fileId);
        $token = Str::random(40);

        $fileShare = new FileShare();
        $fileShare->user_id = Auth::id();
        $fileShare->file_id = $fileId;
        $fileShare->token = $token;
        // $fileShare->access_level = $request->access_level; // e.g., 'public', 'private', etc.
        $fileShare->save();

        // Return the shareable link
        $link = url("/shared-file/{$token}");
        return response()->json(['link' => $link], 200);
    }

    // public function accessSharedFile($token)
    // {
    //     // Find the shared file by token
    //     $fileShare = FileShare::where('token', $token)->firstOrFail();

    //     // Check access level and grant access accordingly
    //     if ($fileShare->access_level === 'public') {
    //         $file = $fileShare->file;
    //         return response()->json(['file' => $file], 200);
    //     }

    //     // Add other access control mechanisms as needed

    //     return response()->json(['message' => 'Access denied'], 403);
    // }
    public function accessSharedFile($token)
    {
        // Find the shared file by token
        $fileShare = FileShare::where('token', $token)->first();

        // Check if the file share exists
        if ($fileShare) {
            // Return the associated file
            $file = $fileShare->file;
            return response()->json(['file' => $file], 200);
        }

        // If file share doesn't exist, return access denied
        return response()->json(['message' => 'Access denied'], 403);
    }



    public function store(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:files,id',
            'email' => 'required|email|exists:users,email',
            'access_control' => 'required|in:public,private,restricted',
        ]);
    
        $token = Str::random(40);
    
        $sharedWithUser = User::where('email', $request->email)->first();
    
        FileShare::create([
            'user_id' => Auth::id(),
            'file_id' => $request->file_id,
            'shared_with_user_id' => $sharedWithUser->id,
            'access_control' => $request->access_control,
            'token' => $token, 
        ]);
    
        return response()->json(['message' => 'File shared successfully', 'token' => $token], 201);
    }

    public function sharedWithMe()
    {
        $user = Auth::user();
        $sharedFiles = FileShare::where('shared_with_user_id', $user->id)->with('file')->get();
        
        $sharedFiles->each(function ($fileShare) {
            $file = $fileShare->file;
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });
        return response()->json($sharedFiles);
    }
}