<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class FolderController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);


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



    public function fetchRootContents(Request $request)
    {

        $userId = $request->user()->id;

        $files = File::whereNull('folder_id')->where('user_id', $userId)->get();
        $folders = Folder::whereNull('parent_id')->where('user_id', $userId)->get();

        if ($files->isEmpty() && $folders->isEmpty()) {
            return response()->json(['error' => 'No files or folders found at root level'], 404);
        }

        $files->each(function ($file) {
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });
        $response = [
            'files' => $files,
            'folders' => $folders,
        ];
        return response()->json($response);
    }




    public function fetchContents(Folder $folder)
    {

        if ($folder->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to access this folder'], 403);
        }


        $files = File::where('folder_id', $folder->id)->get();

        $folders = $folder->children()->get();


        $files->each(function ($file) {
            $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        });


        if ($files->isEmpty() && $folders->isEmpty()) {
            return response()->json(['message' => 'No files or folders found in this directory'], 404);
        }

        return response()->json(['files' => $files, 'folders' => $folders]);
    }




    public function show(Folder $folder)
    {
        return response()->json($folder);
    }


    public function index()
    {
        $folders = Folder::where('user_id', Auth::id())->get();
        return response()->json($folders);
    }


    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


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

        foreach ($folder->files as $file) {
            $file->delete();
        }


        foreach ($folder->children as $subFolder) {
            $this->deleteFolderAndContents($subFolder);
        }


        $folder->delete();
    }

}