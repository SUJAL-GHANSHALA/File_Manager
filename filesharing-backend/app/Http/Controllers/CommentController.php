<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, File $file)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Create a new comment associated with the authenticated user and the file
        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->file_id = $file->id;
        $comment->content = $request->input('content');
        $comment->save();

        return response()->json($comment, 201);
    }

    public function index(File $file)
    {
        // Retrieve all comments associated with the file
        $comments = $file->comments()->with('user')->get();

        return response()->json($comments);
    }
}