<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{

    public function index(Request $request): View
    {
        $q = $request->input('q');
        $catalogs = Catalog::query()
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('catalogs.index', compact('catalogs', 'q'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:catalogs,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Catalog::create($validated);

        return back()->with('status', 'Danh mục đã được tạo.');
    }

    public function update(Request $request, Catalog $catalog): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:catalogs,name,' . $catalog->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $catalog->update($validated);

        return back()->with('status', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Catalog $catalog): RedirectResponse
    {
        $hasFiles = File::where('catalog_id', $catalog->id)->exists();

        if ($hasFiles) {
            $catalog->update(['is_active' => false]);
            return back()->with('status', 'Danh mục đang được sử dụng nên đã chuyển sang trạng thái Ngưng hoạt động.');
        }

        $catalog->delete();
        return back()->with('status', 'Đã xoá danh mục.');
    }

    public function toggle(Catalog $catalog): RedirectResponse
    {
        $catalog->update(['is_active' => ! $catalog->is_active]);
        return back()->with('status', 'Đã cập nhật trạng thái danh mục.');
    }
}
