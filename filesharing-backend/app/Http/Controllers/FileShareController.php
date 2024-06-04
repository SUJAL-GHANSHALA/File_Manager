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

    // Method to handle sharing a file
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file_id' => 'required|exists:files,id', // file_id is required and must exist in the files table
            'email' => 'required|email|exists:users,email', // email is required, must be a valid email format, and must exist in the users table
            'access_control' => 'required|in:public,private,restricted', // access_control is required and must be one of the specified values
        ]);
    
        // Generate a random token for sharing the file
        $token = Str::random(40);
    
        // Find the user with the provided email
        $sharedWithUser = User::where('email', $request->email)->first();
    
        // Create a new FileShare record in the database
        FileShare::create([
            'user_id' => Auth::id(), // ID of the user who is sharing the file
            'file_id' => $request->file_id, // ID of the file being shared
            'shared_with_user_id' => $sharedWithUser->id, // ID of the user the file is being shared with
            'access_control' => $request->access_control, // Access control setting for the shared file
            'token' => $token, // Generated token for accessing the shared file
        ]);
    
        // Return a JSON response indicating the file was shared successfully, including the generated token
        return response()->json(['message' => 'File shared successfully', 'token' => $token], 201);
    }

    // Method to retrieve files shared with the authenticated user
    public function sharedWithMe()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Retrieve all FileShare records where the authenticated user is the shared_with_user_id
        $sharedFiles = FileShare::where('shared_with_user_id', $user->id)->with('file')->get();
        
        // Iterate through each FileShare record to generate a URL for the file
        $sharedFiles->each(function ($fileShare) {
            $file = $fileShare->file;
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });
        
        // Return the shared files as a JSON response
        return response()->json($sharedFiles);
    }
}
