<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    /**
     * Display a listing of folders
     */
    public function index()
    {
        $folders = Folder::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->withCount('files')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('folders.index', compact('folders'));
    }

    /**
     * Store a newly created folder
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $folder = Folder::create([
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully.',
            'folder' => $folder,
        ]);
    }

    /**
     * Display the specified folder
     */
    public function show($id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->withCount('files')
            ->findOrFail($id);
        
        $subfolders = Folder::where('parent_id', $id)
            ->where('user_id', Auth::id())
            ->withCount('files')
            ->get();
        
        $files = $folder->files()
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('folders.show', compact('folder', 'subfolders', 'files'));
    }

    /**
     * Update the specified folder
     */
    public function update(Request $request, $id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $folder->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Folder updated successfully.',
            'folder' => $folder,
        ]);
    }

    /**
     * Remove the specified folder
     */
    public function destroy($id)
    {
        $folder = Folder::where('user_id', Auth::id())
            ->findOrFail($id);
        
        // Check if folder has files
        if ($folder->files()->whereNull('deleted_at')->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete folder with files. Please delete or move the files first.',
            ], 422);
        }
        
        // Check if folder has subfolders
        if ($folder->subfolders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete folder with subfolders. Please delete the subfolders first.',
            ], 422);
        }
        
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder deleted successfully.',
        ]);
    }
}
