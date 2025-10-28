<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 15);
        $query = Author::query()
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->orderBy('name');
        $authors = $query->paginate($perPage)->appends($request->query());
        return response()->json([
            'data' => $authors->items(),
            'meta' => [
                'current_page' => $authors->currentPage(),
                'last_page' => $authors->lastPage(),
                'per_page' => $authors->perPage(),
                'total' => $authors->total(),
            ],
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'contact' => 'nullable|string',
        ]);
        $author = Author::create($validated);
        return response()->json(['message' => 'Tạo tác giả thành công.', 'data' => $author], Response::HTTP_CREATED);
    }
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'contact' => 'nullable|string',
        ]);
        $author->update($validated);
        return response()->json(['message' => 'Cập nhật tác giả thành công.', 'data' => $author]);
    }
    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json(['message' => 'Xoá tác giả thành công.']);
    }
}