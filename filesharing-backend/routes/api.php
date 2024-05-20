<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;


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

// sanctum auth middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // folder routes
    Route::post('/folders', [FolderController::class, 'store']); // create folder
    Route::get('/folders/{folder}', [FolderController::class, 'show']); // show folder details
    Route::get('/folders', [FolderController::class, 'index']); // list all folders
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy']); // delete folder

    // api route for fetching contents of the root folder
    Route::get('/folders/root/contents', [FolderController::class, 'fetchRootContents']);
    // fetch folder contents
    Route::get('/folders/{folder}/contents', [FolderController::class, 'fetchContents']);


    //file routes
    Route::post('/files', [FileController::class, 'store']); // upload file
    Route::delete('/files/{file}', [FileController::class, 'destroy']); // delete file
    Route::get('/files/{file}', [FileController::class, 'show']); // show file details
    Route::get('/files', [FileController::class, 'index']); // list all files
});
