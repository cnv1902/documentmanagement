<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CatalogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Authentication routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard and Home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Files
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{id}', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
    Route::put('/files/{id}', [FileController::class, 'update'])->name('files.update');
    Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::post('/files/{id}/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/files/{id}/force', [FileController::class, 'forceDelete'])->name('files.forceDelete');
    
    // Catalogs (Danh mục tài liệu)
    Route::get('/catalogs', [CatalogController::class, 'index'])->name('catalogs.index');
    Route::post('/catalogs', [CatalogController::class, 'store'])->name('catalogs.store');
    Route::put('/catalogs/{catalog}', [CatalogController::class, 'update'])->name('catalogs.update');
    Route::delete('/catalogs/{catalog}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
    Route::post('/catalogs/{catalog}/toggle', [CatalogController::class, 'toggle'])->name('catalogs.toggle');
    
    // Special pages
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
    
    // Profile & Settings
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');
    
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');
    
    Route::get('/storage/upgrade', function () {
        return view('storage.upgrade');
    })->name('storage.upgrade');
    
    // Legal pages
    Route::get('/privacy-policy', function () {
        return view('legal.privacy');
    })->name('privacy-policy');
    
    Route::get('/terms-of-service', function () {
        return view('legal.terms');
    })->name('terms-of-service');
    
    // Password reset
    Route::get('/password/reset', function () {
        return view('auth.passwords.request');
    })->name('password.request');
});

