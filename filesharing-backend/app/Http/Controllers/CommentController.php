<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Comment;

class CommentController extends Controller
{
    // Method to handle storing a new comment
    public function store(Request $request, File $file)
    {
        // Validate the incoming request
        $request->validate([
            'content' => 'required|string',
        ]);

        // Create a new comment associated with the authenticated user and the file
        $comment = new Comment();
        $comment->user_id = auth()->user()->id; // Set the user ID from the authenticated user
        $comment->file_id = $file->id; // Set the file ID from the given file
        $comment->content = $request->input('content'); // Set the comment content from the request
        $comment->save(); // Save the comment to the database

        // Return the newly created comment as a JSON response with a 201 (Created) status
        return response()->json($comment, 201);
    }

    // Method to retrieve all comments for a specific file
    public function index(File $file)
    {
        // Retrieve all comments associated with the file, including the user who made each comment
        $comments = $file->comments()->with('user')->get();

        // Return the comments as a JSON response
        return response()->json($comments);
    }
}
