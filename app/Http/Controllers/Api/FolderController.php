<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    /**
     * Get all folders
     */
    public function index(Request $request)
    {
        $query = Folder::where('user_id', Auth::id());
        
        if ($request->has('parent_id')) {
            if ($request->parent_id === 'null' || $request->parent_id === null) {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }
        
        $folders = $query->withCount('files')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $folders,
        ]);
    }

    /**
     * Get a single folder
     */
    public function show($id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->withCount('files')
            ->findOrFail($id);
        
        // Get subfolders
        $subfolders = Folder::where('parent_id', $id)
            ->where('user_id', Auth::id())
            ->withCount('files')
            ->get();
        
        // Get files in this folder
        $files = $folder->files()
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'folder' => $folder,
                'subfolders' => $subfolders,
                'files' => $files,
            ],
        ]);
    }

    /**
     * Create a new folder
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if parent folder belongs to user
        if ($request->parent_id) {
            $parentFolder = Folder::where('id', $request->parent_id)
                ->where('user_id', Auth::id())
                ->first();
            
            if (!$parentFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent folder not found',
                ], 404);
            }
        }

        $folder = Folder::create([
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully',
            'data' => $folder,
        ], 201);
    }

    /**
     * Update folder
     */
    public function update(Request $request, $id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Prevent moving folder to itself or its subfolder
        if ($request->has('parent_id') && $request->parent_id) {
            if ($request->parent_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot move folder to itself',
                ], 422);
            }
            
            // Check if parent belongs to user
            $parentFolder = Folder::where('id', $request->parent_id)
                ->where('user_id', Auth::id())
                ->first();
            
            if (!$parentFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent folder not found',
                ], 404);
            }
        }

        $folder->update($request->only(['name', 'parent_id']));

        return response()->json([
            'success' => true,
            'message' => 'Folder updated successfully',
            'data' => $folder,
        ]);
    }

    /**
     * Delete folder
     */
    public function destroy($id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->findOrFail($id);
        
        // Check if folder has files
        $filesCount = $folder->files()->whereNull('deleted_at')->count();
        if ($filesCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete folder with files. Please delete or move the files first.',
            ], 422);
        }
        
        // Check if folder has subfolders
        $subfoldersCount = $folder->subfolders()->count();
        if ($subfoldersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete folder with subfolders. Please delete the subfolders first.',
            ], 422);
        }
        
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder deleted successfully',
        ]);
    }

    /**
     * Get folder tree
     */
    public function tree()
    {
        $folders = Folder::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->with('subfolders')
            ->withCount('files')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $folders,
        ]);
    }
}
