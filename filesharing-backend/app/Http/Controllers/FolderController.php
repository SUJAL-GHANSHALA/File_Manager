<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // create a new folder
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);
    
        // chking folder with the same name and parent_id
        $existingFolder = Folder::where('name', $request->name)
                                ->where('parent_id', $request->parent_id)
                                ->first();
    
        if ($existingFolder) {
            return response()->json(['error' => 'A folder with the same name already exists in this directory.'], 400);
        }
    
        $folder = new Folder();
        $folder->name = $request->name;
        $folder->user_id = Auth::id();
    
        if ($request->parent_id) {
            $parentFolder = Folder::find($request->parent_id);
            if ($parentFolder->nesting_level >= 6) {
                return response()->json(['error' => 'Maximum nesting level of 6 exceeded.'], 400);
            }
            $folder->parent_id = $request->parent_id;
            $folder->nesting_level = $parentFolder->nesting_level + 1;
            $folder->path = $parentFolder->path . '/' . $folder->name;
        } else {
            $folder->parent_id = null;
            $folder->nesting_level = 1;
            $folder->path = '/' . $folder->name;
        }
    
        $folder->save();
    
        return response()->json($folder, 201);
    }
    

    // fetch contents of the root folder
    public function fetchRootContents(Request $request)
    {
        // Get the authenticated user's ID
        $userId = $request->user()->id;
    
        // Retrieve files and folders at root level (where folder_id is null for files or parent_id is null for folders)
        $files = File::whereNull('folder_id')->where('user_id', $userId)->get();
        $folders = Folder::whereNull('parent_id')->where('user_id', $userId)->get();
    
        // Check if any files or folders exist
        if ($files->isEmpty() && $folders->isEmpty()) {
            return response()->json(['error' => 'No files or folders found at root level'], 404);
        }
    
        // Prepare the response with files and folders
        $response = [
            'files' => $files,
            'folders' => $folders,
        ];
    
        return response()->json($response);
    }

    
    // fetch folder contents
    // public function fetchContents(Folder $folder)
    // {
    //     // eager load the relationships (subfolders and files)
    //     $folder->load('children', 'files');

    //     // return the folder with its contents
    //     return response()->json($folder);
    // }
    public function fetchContents(Folder $folder){
    // Check if the authenticated user owns the folder
    if ($folder->user_id !== Auth::id()) {
        return response()->json(['error' => 'You are not authorized to access this folder'], 403);
    }
    // Load the files and folders in the specified folder
    $files = $folder->files()->get();
    $folders = $folder->children()->get();

    // Check if both files and folders are empty
    if ($files->isEmpty() && $folders->isEmpty()) {
        return response()->json(['message' => 'No files or folders found in this directory'], 404);
    }
    // Return both files and folders
    return response()->json(['files' => $files, 'folders' => $folders]);
    }



    // show folder details
    public function show(Folder $folder)
    {
        return response()->json($folder);
    }

    // list all folders
    public function index()
    {
        $folders = Folder::where('user_id', Auth::id())->get();
        return response()->json($folders);
    }

    // delete a folder
    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // recursive function to delete subfolders and files
        $this->deleteFolderAndContents($folder);
    
        return response()->json(['message' => 'Folder and its contents deleted successfully.']);
    }
    
    /**
     * Recursive function to delete a folder and its contents.
     *
     * @param Folder $folder
     */
    private function deleteFolderAndContents(Folder $folder)
    {
        // delete all files in the folder
        foreach ($folder->files as $file) {
            $file->delete();
        }
    
        // recursively delete all subfolders
        foreach ($folder->children as $subFolder) {
            $this->deleteFolderAndContents($subFolder);
        }
    
        // delete the folder itself
        $folder->delete();
    }
    
}
