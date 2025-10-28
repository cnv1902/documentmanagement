<?php
namespace App\Http\Controllers;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class AuthorController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $authors = Author::query()
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10);
        return view('authors.index', compact('authors'));
    }
    public function create(): View
    {
        return view('authors.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'contact' => 'nullable|string|max:255',
        ]);

        $author = Author::create($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tạo tác giả thành công.', 'data' => $author]);
        }
        return redirect()->route('authors.index')->with('success', 'Tạo tác giả thành công.');
    }
    public function edit(Author $author): View
    {
        return view('authors.edit', compact('author'));
    }
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'contact' => 'nullable|string|max:255',
        ]);

        $author->update($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật tác giả thành công.', 'data' => $author]);
        }
        return redirect()->route('authors.index')->with('success', 'Cập nhật tác giả thành công.');
    }
    public function destroy(Request $request, Author $author)
    {
        $author->delete();
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Xoá tác giả thành công.']);
        }
        return redirect()->route('authors.index')->with('success', 'Xoá tác giả thành công.');
    }
}
