<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    // Function to handle the creation of a new folder
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255', // Name is required, must be a string, and max length of 255
            'parent_id' => 'nullable|exists:folders,id', // Parent ID is optional, but if provided, must exist in the folders table
        ]);

        // Check if a folder with the same name already exists in the specified parent directory
        $existingFolder = Folder::where('name', $request->name)
            ->where('parent_id', $request->parent_id)
            ->first();

        // If such a folder exists, return an error response
        if ($existingFolder) {
            return response()->json(['error' => 'A folder with the same name already exists in this directory.'], 400);
        }

        // Create a new Folder instance
        $folder = new Folder();
        $folder->name = $request->name; // Set the folder name
        $folder->user_id = Auth::id(); // Set the user ID to the ID of the currently authenticated user

        // If a parent ID is provided, set the nesting level and path based on the parent folder
        if ($request->parent_id) {
            $parentFolder = Folder::find($request->parent_id);
            if ($parentFolder->nesting_level >= 6) {
                return response()->json(['error' => 'Maximum nesting level of 6 exceeded.'], 400);
            }
            $folder->parent_id = $request->parent_id; // Set the parent ID
            $folder->nesting_level = $parentFolder->nesting_level + 1; // Increment the nesting level
            $folder->path = $parentFolder->path . '/' . $folder->name; // Set the folder path
        } else {
            $folder->parent_id = null; // No parent ID, meaning this is a root folder
            $folder->nesting_level = 1; // Set nesting level to 1 for root folder
            $folder->path = '/' . $folder->name; // Set the folder path
        }

        // Save the folder to the database
        $folder->save();

        // Return a JSON response with the created folder data and a 201 status code
        return response()->json($folder, 201);
    }

    // Function to fetch the contents of the root directory for the authenticated user
    public function fetchRootContents(Request $request)
    {
        $userId = $request->user()->id; // Get the ID of the authenticated user

        // Get all files and folders at the root level for the authenticated user
        $files = File::whereNull('folder_id')->where('user_id', $userId)->get();
        $folders = Folder::whereNull('parent_id')->where('user_id', $userId)->get();

        // If no files or folders are found, return an error response
        if ($files->isEmpty() && $folders->isEmpty()) {
            return response()->json(['error' => 'No files or folders found at root level'], 404);
        }

        // Generate URLs for the files
        $files->each(function ($file) {
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });

        // Prepare the response data
        $response = [
            'files' => $files,
            'folders' => $folders,
        ];

        // Return the response data as JSON
        return response()->json($response);
    }

    // Function to fetch the contents of a specific folder
    public function fetchContents(Folder $folder)
    {
        // Check if the authenticated user is authorized to access the folder
        if ($folder->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to access this folder'], 403);
        }

        // Get all files in the specified folder
        $files = File::where('folder_id', $folder->id)->get();

        // Get all subfolders in the specified folder
        $folders = $folder->children()->get();

        // Generate URLs for the files
        $files->each(function ($file) {
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });

        // If no files or folders are found, return a message response
        if ($files->isEmpty() && $folders->isEmpty()) {
            return response()->json(['message' => 'No files or folders found in this directory'], 404);
        }

        // Return the files and folders as JSON
        return response()->json(['files' => $files, 'folders' => $folders]);
    }

    // Function to show the details of a specific folder
    public function show(Folder $folder)
    {
        return response()->json($folder); // Return the folder details as JSON
    }

    // Function to list all folders for the authenticated user
    public function index()
    {
        $folders = Folder::where('user_id', Auth::id())->get(); // Get all folders for the authenticated user
        return response()->json($folders); // Return the folders as JSON
    }

    // Function to delete a specific folder
    public function destroy(Folder $folder)
    {
        // Check if the authenticated user is authorized to delete the folder
        if ($folder->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the folder and its contents
        $this->deleteFolderAndContents($folder);

        // Return a success message response
        return response()->json(['message' => 'Folder and its contents deleted successfully.']);
    }

    /**
     * Recursive function to delete a folder and its contents.
     *
     * @param Folder $folder
     */
    private function deleteFolderAndContents(Folder $folder)
    {
        // Delete all files in the folder
        foreach ($folder->files as $file) {
            $file->delete();
        }

        // Recursively delete all subfolders and their contents
        foreach ($folder->children as $subFolder) {
            $this->deleteFolderAndContents($subFolder);
        }

        // Delete the folder itself
        $folder->delete();
    }
}
