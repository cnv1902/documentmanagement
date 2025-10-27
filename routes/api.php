<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\CatalogController;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // File endpoints
    Route::get('/files', [FileController::class, 'index']);
    Route::post('/files', [FileController::class, 'store']);
    Route::get('/files/{id}', [FileController::class, 'show']);
    Route::put('/files/{id}', [FileController::class, 'update']);
    Route::delete('/files/{id}', [FileController::class, 'destroy']);
    Route::get('/files/{id}/download', [FileController::class, 'download']);
    
    // File special actions
    Route::get('/files/trash/list', [FileController::class, 'trash']);
    Route::post('/files/{id}/restore', [FileController::class, 'restore']);
    Route::delete('/files/{id}/force', [FileController::class, 'forceDelete']);
    Route::get('/files/favourites/list', [FileController::class, 'favourites']);
    Route::get('/files/recent/list', [FileController::class, 'recent']);
    
    // Catalog endpoints
    Route::get('/catalogs', [CatalogController::class, 'index']);
    Route::post('/catalogs', [CatalogController::class, 'store']);
    Route::put('/catalogs/{catalog}', [CatalogController::class, 'update']);
    Route::delete('/catalogs/{catalog}', [CatalogController::class, 'destroy']);
    Route::post('/catalogs/{catalog}/toggle', [CatalogController::class, 'toggle']);
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

