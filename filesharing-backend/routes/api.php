<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileShareController;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// public routes
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // folder routes
    Route::post('/folders', [FolderController::class, 'store']); // create folder
    Route::get('/folders/{folder}', [FolderController::class, 'show']); // show folder details
    Route::get('/folders', [FolderController::class, 'index']); // list all folders
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy']); // delete folder

   //getting contents of folders
    Route::get('/folders/root/contents', [FolderController::class, 'fetchRootContents']);
    Route::get('/folders/{folder}/contents', [FolderController::class, 'fetchContents']);


    //file routes
    Route::post('/files', [FileController::class, 'store']); // upload file
    Route::delete('/files/{file}', [FileController::class, 'destroy']); // delete file
    Route::get('/files/{file}', [FileController::class, 'show']); // show file details
    Route::get('/files', [FileController::class, 'index']); // list all files
    Route::put('/files/{file}/rename', [FileController::class, 'rename']);  // Rename file route
    Route::post('/files/search', [FileController::class, 'search']);  //search file routes
    Route::post('/files/{file}/move', [FileController::class, 'move']); // movin a file route/
    Route::put('/files/{file}/star', [FileController::class, 'toggleStarred']); // add star route
    Route::post('/files/starred', [FileController::class, 'getStarredFiles']); //get starred files

    //file share routes
    Route::post('/file-share', [FileShareController::class, 'store']);
    Route::get('/shared-with-me', [FileShareController::class, 'sharedWithMe']);

    //comments routes
    Route::post('/files/{file}/comments', [CommentController::class, 'store']);
    Route::get('/files/{file}/comments', [CommentController::class, 'index']);
});