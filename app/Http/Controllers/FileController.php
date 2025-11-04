<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Catalog;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $catalogId = $request->input('catalog_id');
        $publisherId = $request->input('publisher_id');
        $approved = $request->input('approved');

        $files = File::query()
            ->with(['catalog', 'authors', 'publisher'])
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->when($catalogId, fn($builder) => $builder->where('catalog_id', $catalogId))
            ->when($publisherId, fn($builder) => $builder->where('publisher_id', $publisherId))
            ->when($approved !== null, fn($builder) => $builder->where('approved', $approved))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $catalogs = Catalog::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('file.index', compact('files', 'catalogs', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return back()->withErrors(['auth' => 'Bạn cần đăng nhập trước khi upload file.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:51200',
            'catalog_id' => 'nullable|exists:catalogs,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'approved' => 'nullable',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('files', 'public');

        $file = File::create([
            'user_id' => auth()->id(), 
            'name' => $validated['name'],
            'filename' => $uploadedFile->getClientOriginalName(), 
            'original_name' => $uploadedFile->getClientOriginalName(), 
            'path' => $path,
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
            'catalog_id' => $validated['catalog_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'approved' => $request->boolean('approved'),
        ]);

        $file->authors()->attach($validated['author_ids']);

        return redirect()->route('files.index')->with('success', 'Upload file thành công.');
    }

    public function edit(File $file): View
    {
        $catalogs = Catalog::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        
        return view('file.edit', compact('file', 'catalogs', 'authors', 'publishers'));
    }

    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'catalog_id' => 'nullable|exists:catalogs,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'approved' => 'nullable',
            'is_favourite' => 'nullable',
        ]);

        $file->update([
            'name' => $validated['name'],
            'catalog_id' => $validated['catalog_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'approved' => $request->boolean('approved'),
            'is_favourite' => $request->boolean('is_favourite'),
        ]);

        $file->authors()->sync($validated['author_ids']);

        return redirect()->route('files.index')->with('success', 'Cập nhật file thành công.');
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();
        
        return redirect()->route('files.index')->with('success', 'Xoá file thành công.');
    }
}
