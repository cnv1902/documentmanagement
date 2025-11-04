<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/files', [FileController::class, 'index'])->name('files.index');

    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');

    Route::get('/files/{file}', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');

    Route::get('/catalogs', [CatalogController::class, 'index'])->name('catalogs.index');
    Route::post('/catalogs', [CatalogController::class, 'store'])->name('catalogs.store');
    Route::put('/catalogs/{catalog}', [CatalogController::class, 'update'])->name('catalogs.update');
    Route::delete('/catalogs/{catalog}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
    Route::post('/catalogs/{catalog}/toggle', [CatalogController::class, 'toggle'])->name('catalogs.toggle');
    
    Route::get('/publishers', [PublisherController::class, 'index'])->name('publishers.index');
    Route::get('/publishers/create', [PublisherController::class, 'create'])->name('publishers.create');
    Route::post('/publishers', [PublisherController::class, 'store'])->name('publishers.store');
    Route::get('/publishers/{publisher}/edit', [PublisherController::class, 'edit'])->name('publishers.edit');
    Route::put('/publishers/{publisher}', [PublisherController::class, 'update'])->name('publishers.update');
    Route::delete('/publishers/{publisher}', [PublisherController::class, 'destroy'])->name('publishers.destroy');

    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('/authors/create', [AuthorController::class, 'create'])->name('authors.create');
    Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
    Route::get('/authors/{author}/edit', [AuthorController::class, 'edit'])->name('authors.edit');
    Route::put('/authors/{author}', [AuthorController::class, 'update'])->name('authors.update');
    Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');

    Route::get('/recent', function () {
        return view('pages.recent');
    })->name('recent');
    
    Route::get('/favourites', function () {
        return view('pages.favourites');
    })->name('favourites');
    
    Route::get('/trash', function () {
        return view('pages.trash');
    })->name('trash');
    
    Route::get('/search', function () {
        return view('pages.search');
    })->name('search');
    
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');
    
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');
    
    Route::get('/storage/upgrade', function () {
        return view('storage.upgrade');
    })->name('storage.upgrade');
    
    Route::get('/privacy-policy', function () {
        return view('legal.privacy');
    })->name('privacy-policy');
    
    Route::get('/terms-of-service', function () {
        return view('legal.terms');
    })->name('terms-of-service');
    
    Route::get('/password/reset', function () {
        return view('auth.passwords.request');
    })->name('password.request');
    
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::put('/files/{file}', [FileController::class, 'update'])->name('files.update');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});

