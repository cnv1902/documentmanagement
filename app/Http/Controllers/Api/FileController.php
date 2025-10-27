<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Get all files
     */
    public function index(Request $request)
    {
        $query = File::where('user_id', Auth::id())
            ->whereNull('deleted_at');
        
        if ($request->has('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('sort_by')) {
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($request->sort_by, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 20);
        $files = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    /**
     * Get a single file
     */
    public function show($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $file,
        ]);
    }

    /**
     * Upload a new file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // Max 100MB
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $uploadedFile = $request->file('file');
        $user = Auth::user();

        // Check storage limit
        $fileSize = $uploadedFile->getSize();
        if (($user->storage_used + $fileSize) > $user->storage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough storage space',
            ], 422);
        }

        // Store file
        $filename = Str::random(40) . '.' . $uploadedFile->getClientOriginalExtension();
        $path = $uploadedFile->storeAs('files/' . $user->id, $filename, 'local');

        // Create file record
        $file = File::create([
            'user_id' => $user->id,
            'folder_id' => $request->folder_id,
            'name' => $uploadedFile->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
        ]);

        // Update user storage
        $user->storage_used = $user->storage_used + $uploadedFile->getSize();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => $file,
        ], 201);
    }

    /**
     * Update file information
     */
    public function update(Request $request, $id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'folder_id' => 'nullable|exists:folders,id',
            'is_favourite' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file->update($request->only(['name', 'folder_id', 'is_favourite']));

        return response()->json([
            'success' => true,
            'message' => 'File updated successfully',
            'data' => $file,
        ]);
    }

    /**
     * Download a file
     */
    public function download($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        if (!Storage::disk('local')->exists($file->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on storage',
            ], 404);
        }
        
        return response()->download(storage_path('app/' . $file->path), $file->name);
    }

    /**
     * Move file to trash (soft delete)
     */
    public function destroy($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        $file->deleted_at = now();
        $file->save();

        return response()->json([
            'success' => true,
            'message' => 'File moved to trash',
        ]);
    }

    /**
     * Get trashed files
     */
    public function trash()
    {
        $files = File::where('user_id', Auth::id())
            ->whereNotNull('deleted_at')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    /**
     * Restore file from trash
     */
    public function restore($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNotNull('deleted_at')
            ->findOrFail($id);
        
        $file->deleted_at = null;
        $file->save();

        return response()->json([
            'success' => true,
            'message' => 'File restored successfully',
            'data' => $file,
        ]);
    }

    /**
     * Permanently delete file
     */
    public function forceDelete($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNotNull('deleted_at')
            ->findOrFail($id);
        
        // Delete physical file
        if (Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }
        
        // Update user storage
        $user = Auth::user();
        $user->storage_used = $user->storage_used - $file->size;
        $user->save();
        
        // Delete record
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File permanently deleted',
        ]);
    }

    /**
     * Get favourite files
     */
    public function favourites()
    {
        $files = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_favourite', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    /**
     * Get recent files
     */
    public function recent()
    {
        $files = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }
}
