<?php

use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\AuthorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\CatalogController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    Route::get('/files', [FileController::class, 'index']);
    Route::post('/files', [FileController::class, 'store']);
    Route::get('/files/{id}', [FileController::class, 'show']);
    Route::put('/files/{id}', [FileController::class, 'update']);
    Route::delete('/files/{id}', [FileController::class, 'destroy']);
    Route::get('/files/{id}/download', [FileController::class, 'download']);
    
    Route::get('/files/trash/list', [FileController::class, 'trash']);
    Route::post('/files/{id}/restore', [FileController::class, 'restore']);
    Route::delete('/files/{id}/force', [FileController::class, 'forceDelete']);
    Route::get('/files/favourites/list', [FileController::class, 'favourites']);
    Route::get('/files/recent/list', [FileController::class, 'recent']);
    Route::post('/files/{id}/approve', [FileController::class, 'approve']);
    Route::post('/files/{id}/unapprove', [FileController::class, 'unapprove']);

    Route::get('/catalogs', [CatalogController::class, 'index']);
    Route::post('/catalogs', [CatalogController::class, 'store']);
    Route::put('/catalogs/{catalog}', [CatalogController::class, 'update']);
    Route::delete('/catalogs/{catalog}', [CatalogController::class, 'destroy']);
    Route::post('/catalogs/{catalog}/toggle', [CatalogController::class, 'toggle']);

    Route::get('/publishers', [PublisherController::class, 'index']);
    Route::post('/publishers', [PublisherController::class, 'store']);
    Route::put('/publishers/{publisher}', [PublisherController::class, 'update']);
    Route::delete('/publishers/{publisher}', [PublisherController::class, 'destroy']);

    Route::get('/authors', [AuthorController::class, 'index']);
    Route::post('/authors', [AuthorController::class, 'store']);
    Route::put('/authors/{author}', [AuthorController::class, 'update']);
    Route::delete('/authors/{author}', [AuthorController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

