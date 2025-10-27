<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of files
     */
    public function index(Request $request)
    {
        $query = File::where('user_id', Auth::id())
            ->whereNull('deleted_at');
        
        // Filter by folder
        if ($request->has('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }
        
        // Search
        if ($request->has('q') && $request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $files = $query->paginate(20);
        
        return view('files.index', compact('files'));
    }

    /**
     * Show the form for creating a new file
     */
    public function create()
    {
        $folders = Auth::user()->folders;
        return view('files.create', compact('folders'));
    }

    /**
     * Store a newly created file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // Max 100MB
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $uploadedFile = $request->file('file');
        $user = Auth::user();

        // Check storage limit
        if (($user->storage_used + $uploadedFile->getSize()) > $user->storage_limit) {
            return redirect()->back()
                ->withErrors(['file' => 'Not enough storage space.'])
                ->withInput();
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

        return redirect()->route('files.index')
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified file
     */
    public function show($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        return view('files.show', compact('file'));
    }

    /**
     * Download the specified file
     */
    public function download($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        return response()->download(storage_path('app/' . $file->path), $file->name);
    }

    /**
     * Update the specified file
     */
    public function update(Request $request, $id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file->update([
            'name' => $request->name,
            'folder_id' => $request->folder_id,
        ]);

        return redirect()->back()
            ->with('success', 'File updated successfully.');
    }

    /**
     * Remove the specified file (soft delete)
     */
    public function destroy($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->findOrFail($id);
        
        $file->update(['deleted_at' => now()]);

        return redirect()->back()
            ->with('success', 'File moved to trash.');
    }

    /**
     * Permanently delete the file
     */
    public function forceDelete($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNotNull('deleted_at')
            ->findOrFail($id);
        
        // Delete physical file
        Storage::disk('local')->delete($file->path);
        
        // Update user storage
        $user = Auth::user();
        $user->storage_used = $user->storage_used - $file->size;
        $user->save();
        
        // Delete record
        $file->delete();

        return redirect()->back()
            ->with('success', 'File permanently deleted.');
    }

    /**
     * Restore a soft deleted file
     */
    public function restore($id)
    {
        $file = File::where('user_id', Auth::id())
            ->whereNotNull('deleted_at')
            ->findOrFail($id);
        
        $file->update(['deleted_at' => null]);

        return redirect()->back()
            ->with('success', 'File restored successfully.');
    }
}
