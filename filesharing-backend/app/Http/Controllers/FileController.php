<?php

namespace App\Http\Controllers;

// Import necessary classes
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Method to handle file upload
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file|max:204800|mimes:jpg,jpeg,png,gif,doc,docx,pdf',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        // Retrieve the uploaded file and its details
        $uploadedFile = $request->file('file');
        $fileName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();
        $mime = $uploadedFile->getMimeType();
        $folderId = $request->folder_id;
        $userId = Auth::id();

        $folderPath = '';

        // Determine the folder path
        if ($folderId) {
            $folder = Folder::find($folderId);
            if (!$folder) {
                return response()->json(['error' => 'The specified folder does not exist.'], 404);
            }
            $folderPath = $folder->path;
        } else {
            $foldersExist = Folder::exists();
            if (!$foldersExist) {
                $folderPath = '';
            } else {
                $rootFolder = Folder::whereNull('parent_id')->first();
                if (!$rootFolder) {
                    return response()->json(['error' => 'Root folder does not exist.'], 404);
                }
                $folderPath = $rootFolder->path;
            }
        }

        // Check for existing file and create a unique file name if necessary
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $filePath = 'public' . $folderPath . '/' . $fileName;

        $counter = 1;
        while (Storage::exists($filePath)) {
            $newFileName = $baseName . '(' . $counter . ')' . ($extension ? '.' . $extension : '');
            $filePath = 'public' . $folderPath . '/' . $newFileName;
            $counter++;
        }

        // Store the file with the unique file name
        $fileLocation = $uploadedFile->storeAs('public' . $folderPath, basename($filePath));
        $location = storage_path('app/' . $fileLocation);

        // Save the file details in the database
        $file = new File();
        $file->name = basename($filePath);
        $file->extension = $extension;
        $file->size = $size;
        $file->mime = $mime;
        $file->folder_id = $folderId;
        $file->user_id = $userId;
        $file->path = $fileLocation;
        $file->location = $location;
        $file->save();

        return response()->json($file, 201);
    }

    // Method to delete a file
    public function destroy($id)
    {
        $file = File::findOrFail($id);

        Storage::delete($file->path);

        $file->delete();

        return response()->json(null, 204);
    }

    // Method to show a file's details
    public function show($id)
    {
        $file = File::findOrFail($id);
        $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));

        // Access the user's name through the relationship
        $user_name = $file->user->name;
        $file->user_name = $user_name;
        // It was giving entire user object before, so unset and now only username
        unset($file->user);
        return response()->json($file);
    }

    // Method to list all files for the authenticated user
    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();
        return response()->json($files);
    }

    // Method to rename a file
    public function rename(Request $request, File $file)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $newName = $request->input('name');
        $extension = pathinfo($file->name, PATHINFO_EXTENSION);
        $newNameWithoutExtension = pathinfo($newName, PATHINFO_FILENAME);
        $newNameWithExtension = $extension ? $newNameWithoutExtension . '.' . $extension : $newNameWithoutExtension;
        $newPath = dirname($file->path) . '/' . $newNameWithExtension;

        // Ensure the new file name is unique by checking for existing files
        if (Storage::exists($newPath)) {
            return response()->json(['error' => 'File name already exists.'], 400);
        }

        // Rename file in storage
        Storage::move($file->path, $newPath);

        // Update file attributes in the database
        $file->name = $newNameWithExtension;
        $file->path = $newPath;
        $file->location = storage_path('app/' . $newPath);
        $file->save();

        return response()->json($file);
    }

    // Method to search for files by name
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');
        $userId = Auth::id();

        $files = File::where('user_id', $userId)
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%'])
            ->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => 'No files found for the given query.'], 404);
        }

        return response()->json($files);
    }

    // Method to move a file to a different folder
    public function move(Request $request, $id)
    {
        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $file = File::findOrFail($id);
        $newFolderId = $request->input('folder_id');
        $newFolder = $newFolderId ? Folder::find($newFolderId) : null;
        $userId = Auth::id();

        if ($file->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newPath = $newFolder ? $newFolder->path . '/' . $file->name : 'public/' . $file->name;
        $newLocation = storage_path('app/' . $newPath);

        Storage::move($file->path, $newPath);

        $file->folder_id = $newFolderId;
        $file->path = $newPath;
        $file->location = $newLocation;
        $file->save();

        return response()->json($file, 200);
    }

    // Method to toggle the starred status of a file
    public function toggleStarred($id)
    {
        $file = File::findOrFail($id);

        // Check if the authenticated user owns the file
        if ($file->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Toggle the starred status
        $file->is_starred = !$file->is_starred;
        $file->save();

        return response()->json(['message' => 'File starred status updated', 'is_starred' => $file->is_starred]);
    }

    // Method to get all starred files for the authenticated user
    public function getStarredFiles()
    {
        $user_id = Auth::id();
        $starredFiles = File::where('user_id', $user_id)->where('is_starred', true)->get();

        return response()->json($starredFiles);
    }
}
