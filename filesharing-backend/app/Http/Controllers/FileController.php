<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'file' => 'required|file|max:204800|mimes:jpg,jpeg,png,gif,doc,docx,pdf', 
            'folder_id' => 'nullable|exists:folders,id', 
        ]);

        $uploadedFile = $request->file('file');
        $fileName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();
        $mime = $uploadedFile->getMimeType();
        $folderId = $request->folder_id;
        $userId = Auth::id();

        $folderPath = ''; 

      
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

      
        $filePath = $uploadedFile->storeAs('public' . $folderPath, $fileName);
        $location = storage_path('app/public' . $folderPath . '/' . $fileName);

      
        $file = new File();
        $file->name = $fileName;
        $file->extension = $extension;
        $file->size = $size;
        $file->mime = $mime;
        $file->folder_id = $folderId;
        $file->user_id = $userId;
        $file->path = $filePath; 
        $file->location = $location; 
        $file->save();

        return response()->json($file, 201);
    }


    public function destroy($id)
    {
        $file = File::findOrFail($id);

    
        Storage::delete($file->path);

    
        $file->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);
        $file->url = Storage::disk('public')->url(str_replace('public/', '', $file->path));
        return response()->json($file);
    }

    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();
        return response()->json($files);
    }
}