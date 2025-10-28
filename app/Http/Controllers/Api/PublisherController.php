<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 15);
        $query = Publisher::query()
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->orderBy('name');
        $publishers = $query->paginate($perPage)->appends($request->query());
        return response()->json([
            'data' => $publishers->items(),
            'meta' => [
                'current_page' => $publishers->currentPage(),
                'last_page' => $publishers->lastPage(),
                'per_page' => $publishers->perPage(),
                'total' => $publishers->total(),
            ],
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
        ]);
        $publisher = Publisher::create($validated);
        return response()->json(['message' => 'Tạo nhà xuất bản thành công.', 'data' => $publisher], Response::HTTP_CREATED);
    }
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
        ]);
        $publisher->update($validated);
        return response()->json(['message' => 'Cập nhật nhà xuất bản thành công.', 'data' => $publisher]);
    }
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return response()->json(['message' => 'Xoá nhà xuất bản thành công.']);
    }
}