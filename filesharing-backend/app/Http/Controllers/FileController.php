<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|max:204800|mimes:jpg,jpeg,png,gif,doc,docx,pdf', // max size 200MB and allowed file types
    //         'folder_id' => 'nullable|exists:folders,id',
    //     ]);

    //     $uploadedFile = $request->file('file');
    //     $fileName = $uploadedFile->getClientOriginalName();
    //     $extension = $uploadedFile->getClientOriginalExtension();
    //     $size = $uploadedFile->getSize();
    //     $mime = $uploadedFile->getMimeType();
    //     $folderId = $request->folder_id;
    //     $userId = Auth::id();

    //     // check if a file with the same name already exists in the specified folder
    //     $existingFile = File::where('name', $fileName)
    //                         ->where('folder_id', $folderId)
    //                         ->first();

    //     if ($existingFile) {
    //         return response()->json(['error' => 'A file with the same name already exists in this folder.'], 400);
    //     }

    //     // determine the file storage path
    //     $folderPath = Folder::find($folderId)->path;
    //     $filePath = $uploadedFile->storeAs('public' . $folderPath, $fileName);
    //     $location = storage_path('app/public' . $folderPath . '/' . $fileName);

    //     // save file metadata to the database
    //     $file = new File();
    //     $file->name = $fileName;
    //     $file->extension = $extension;
    //     $file->size = $size;
    //     $file->mime = $mime;
    //     $file->folder_id = $folderId;
    //     $file->user_id = $userId;
    //     $file->path = $filePath; // store the file path
    //     $file->location = $location; // store the file location
    //     $file->save();

    //     return response()->json($file, 201);
    // }

    //second new code*

    // public function store(Request $request){
    //     $request->validate([
    //         'file' => 'required|file|max:204800|mimes:jpg,jpeg,png,gif,doc,docx,pdf', // max size 200MB and allowed file types
    //         'folder_id' => 'nullable|exists:folders,id', // Make folder_id nullable
    //     ]);

    //     $uploadedFile = $request->file('file');
    //     $fileName = $uploadedFile->getClientOriginalName();
    //     $extension = $uploadedFile->getClientOriginalExtension();
    //     $size = $uploadedFile->getSize();
    //     $mime = $uploadedFile->getMimeType();
    //     $folderId = $request->folder_id;
    //     $userId = Auth::id();

    //     $folderPath = ''; // Initialize folderPath

    //     // Check if folder_id is provided and valid
    //     if ($folderId) {
    //         // Retrieve folder's path only if folder_id is provided
    //         $folder = Folder::find($folderId);
    //         if (!$folder) {
    //             return response()->json(['error' => 'The specified folder does not exist.'], 404);
    //         }
    //         $folderPath = $folder->path;
    //     } else {
    //         // Set folderPath to root folder if folder_id is null
    //         $rootFolder = Folder::whereNull('parent_id')->first();
    //         if (!$rootFolder) {
    //             return response()->json(['error' => 'Root folder does not exist.'], 404);
    //         }
    //         $folderPath = $rootFolder->path;
    //     }

    //     // determine the file storage path
    //     $filePath = $uploadedFile->storeAs('public' . $folderPath, $fileName);
    //     $location = storage_path('app/public' . $folderPath . '/' . $fileName);

    //     // save file metadata to the database
    //     $file = new File();
    //     $file->name = $fileName;
    //     $file->extension = $extension;
    //     $file->size = $size;
    //     $file->mime = $mime;
    //     $file->folder_id = $folderId;
    //     $file->user_id = $userId;
    //     $file->path = $filePath; // store the file path
    //     $file->location = $location; // store the file location
    //     $file->save();

    //     return response()->json($file, 201);
    // }

    //newest code with
    public function store(Request $request){
        $request->validate([
            'file' => 'required|file|max:204800|mimes:jpg,jpeg,png,gif,doc,docx,pdf', // max size 200MB and allowed file types
            'folder_id' => 'nullable|exists:folders,id', // Make folder_id nullable
        ]);

        $uploadedFile = $request->file('file');
        $fileName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();
        $mime = $uploadedFile->getMimeType();
        $folderId = $request->folder_id;
        $userId = Auth::id();

        $folderPath = ''; // Initialize folderPath

        // Check if folder_id is provided and valid
        if ($folderId) {
            // Retrieve folder's path only if folder_id is provided
            $folder = Folder::find($folderId);
            if (!$folder) {
                return response()->json(['error' => 'The specified folder does not exist.'], 404);
            }
            $folderPath = $folder->path;
        } else {
            // Check if any folders exist
            $foldersExist = Folder::exists();

            if (!$foldersExist) {
                // Treat the file as being in the root folder since no folders exist
                $folderPath = ''; // or whatever your root folder path is
            } else {
                // Root folder exists, find its path
                $rootFolder = Folder::whereNull('parent_id')->first();
                if (!$rootFolder) {
                    return response()->json(['error' => 'Root folder does not exist.'], 404);
                }
                $folderPath = $rootFolder->path;
            }
        }

        // determine the file storage path
        $filePath = $uploadedFile->storeAs('public' . $folderPath, $fileName);
        $location = storage_path('app/public' . $folderPath . '/' . $fileName);

        // save file metadata to the database
        $file = new File();
        $file->name = $fileName;
        $file->extension = $extension;
        $file->size = $size;
        $file->mime = $mime;
        $file->folder_id = $folderId;
        $file->user_id = $userId;
        $file->path = $filePath; // store the file path
        $file->location = $location; // store the file location
        $file->save();

        return response()->json($file, 201);
    }


    public function destroy($id)
    {
        $file = File::findOrFail($id);

        // delete the file from the storage
        Storage::delete($file->path);

        // delete the file record from the database
        $file->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);
        return response()->json($file);
    }

    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();
        return response()->json($files);
    }
}
