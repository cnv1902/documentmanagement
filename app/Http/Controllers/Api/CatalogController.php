<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CatalogController extends Controller
{
    /**
     * GET /api/catalogs
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 15);

        $query = Catalog::query()
            ->when($q, function ($builder) use ($q) {
                $builder->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderBy('name');

        $catalogs = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => $catalogs->items(),
            'meta' => [
                'current_page' => $catalogs->currentPage(),
                'last_page' => $catalogs->lastPage(),
                'per_page' => $catalogs->perPage(),
                'total' => $catalogs->total(),
            ],
        ]);
    }

    /**
     * POST /api/catalogs
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:catalogs,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $catalog = Catalog::create($validated);

        return response()->json([
            'message' => 'Tạo danh mục thành công.',
            'data' => $catalog,
        ], Response::HTTP_CREATED);
    }

    /**
     * PUT /api/catalogs/{catalog}
     */
    public function update(Request $request, Catalog $catalog)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:catalogs,name,' . $catalog->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : $catalog->is_active;

        $catalog->update($validated);

        return response()->json([
            'message' => 'Cập nhật danh mục thành công.',
            'data' => $catalog,
        ]);
    }

    /**
     * DELETE /api/catalogs/{catalog}
     * If in use, mark as inactive instead of deleting.
     */
    public function destroy(Catalog $catalog)
    {
        $hasFiles = File::where('catalog_id', $catalog->id)->exists();

        if ($hasFiles) {
            $catalog->update(['is_active' => false]);
            return response()->json([
                'message' => 'Danh mục đang được sử dụng, đã chuyển sang trạng thái Ngưng hoạt động.',
                'data' => $catalog->fresh(),
            ]);
        }

        $catalog->delete();
        return response()->json([
            'message' => 'Xoá danh mục thành công.',
        ]);
    }

    /**
     * POST /api/catalogs/{catalog}/toggle
     */
    public function toggle(Catalog $catalog)
    {
        $catalog->update(['is_active' => ! $catalog->is_active]);

        return response()->json([
            'message' => 'Cập nhật trạng thái danh mục thành công.',
            'data' => $catalog->fresh(),
        ]);
    }
}
