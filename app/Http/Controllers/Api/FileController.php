<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    /**
     * Display a listing of files
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $catalogId = $request->input('catalog_id');
        $publisherId = $request->input('publisher_id');
        $approved = $request->input('approved');
        $isFavourite = $request->input('is_favourite');
        $perPage = (int) $request->input('per_page', 15);

        $query = File::query()
            ->with(['authors', 'publisher', 'catalog'])
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->when($catalogId, fn($builder) => $builder->where('catalog_id', $catalogId))
            ->when($publisherId, fn($builder) => $builder->where('publisher_id', $publisherId))
            ->when(isset($approved), fn($builder) => $builder->where('approved', $approved))
            ->when(isset($isFavourite), fn($builder) => $builder->where('is_favourite', $isFavourite))
            ->orderByDesc('created_at');

        $files = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => $files->items(),
            'meta' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Store a newly created file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:51200', // 50MB max
            'catalog_id' => 'nullable|exists:catalogs,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'approved' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Upload file
        $uploadedFile = $request->file('file');
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('files', $filename, 'public');

        // Create file record
        $file = File::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'filename' => $filename,
            'path' => $path,
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
            'catalog_id' => $request->catalog_id,
            'publisher_id' => $request->publisher_id,
            'approved' => $request->boolean('approved', false),
        ]);

        // Attach authors (many-to-many)
        if ($request->has('author_ids')) {
            $file->authors()->attach($request->author_ids);
        }

        $file->load(['authors', 'publisher', 'catalog']);

        return response()->json([
            'success' => true,
            'message' => 'Upload file thành công.',
            'data' => $file
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified file
     */
    public function show($id)
    {
        $file = File::with(['authors', 'publisher', 'catalog'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

    return response()->json(['success' => true, 'data' => $file]);
    }

    /**
     * Update the specified file
     */
    public function update(Request $request, $id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'catalog_id' => 'nullable|exists:catalogs,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'is_favourite' => 'nullable|boolean',
            'approved' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update file info
        $file->update($request->only([
            'name',
            'catalog_id',
            'publisher_id',
            'is_favourite',
            'approved'
        ]));

        // Sync authors
        if ($request->has('author_ids')) {
            $file->authors()->sync($request->author_ids);
        }

        $file->load(['authors', 'publisher', 'catalog']);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật file thành công.',
            'data' => $file
        ]);
    }

    /**
     * Soft delete the specified file
     */
    public function destroy($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);
        $file->update(['deleted_at' => now()]);

    return response()->json(['success' => true, 'message' => 'Đã chuyển file vào thùng rác.']);
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);

        if (!Storage::disk('public')->exists($file->path)) {
            return response()->json(['message' => 'File không tồn tại.'], Response::HTTP_NOT_FOUND);
        }

        return Storage::disk('public')->download($file->path, $file->filename);
    }

    /**
     * Get trashed files
     */
    public function trash(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);

        $files = File::with(['authors', 'publisher', 'catalog'])
            ->where('user_id', auth()->id())
            ->whereNotNull('deleted_at')
            ->orderByDesc('deleted_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $files->items(),
            'meta' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Restore file from trash
     */
    public function restore($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);
        $file->update(['deleted_at' => null]);

    return response()->json(['success' => true, 'message' => 'Khôi phục file thành công.']);
    }

    /**
     * Permanently delete file
     */
    public function forceDelete($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);

        // Delete physical file
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Delete record
        $file->authors()->detach();
        $file->delete();

    return response()->json(['success' => true, 'message' => 'Xóa vĩnh viễn file thành công.']);
    }

    /**
     * Get favourite files
     */
    public function favourites(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);

        $files = File::with(['authors', 'publisher', 'catalog'])
            ->where('user_id', auth()->id())
            ->where('is_favourite', true)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $files->items(),
            'meta' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Get recent files
     */
    public function recent(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);

        $files = File::with(['authors', 'publisher', 'catalog'])
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $files->items(),
            'meta' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Approve file
     */
    public function approve($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);
        $file->update(['approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Đã phê duyệt file.',
            'data' => $file
        ]);
    }

    /**
     * Unapprove file
     */
    public function unapprove($id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);
        $file->update(['approved' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ phê duyệt file.',
            'data' => $file
        ]);
    }
}
